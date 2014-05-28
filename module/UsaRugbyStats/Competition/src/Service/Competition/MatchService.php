<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;
use Zend\Form\FormInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Zend\EventManager\EventInterface;

class MatchService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Match Repository
     *
     * @var ObjectRepository
     */
    protected $matchRepository;

    /**
     * Match Object Manager
     *
     * @var ObjectManager
     */
    protected $matchObjectManager;

    /**
     * Form used to create new matches
     *
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing matches
     *
     * @var FormInterface
     */
    protected $updateForm;

    /**
     * List of available MatchTeamEvent types
     *
     * @var array
     */
    protected $availableMatchTeamEventTypes;

    public function findByID($id)
    {
        $id = StaticFilter::execute($id, 'Digits');
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getMatchRepository()->find($id);
    }

    public function fetchAll()
    {
        $adapter = new Selectable($this->getMatchRepository());
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Create new match from form data
     *
     * @param  Form        $form
     * @param  array       $data
     * @return Match|false
     */
    public function create(FormInterface $form, array $data)
    {
        $entityClass = $this->getMatchRepository()->getClassName();

        $argv = new \ArrayObject;
        $argv['entity'] = new $entityClass;
        $argv['form'] = $form;
        $argv['data'] = $data;

        return $this->processRequest('create', $argv);
    }

    /**
     * Update existing match entity with new data
     *
     * @precondition Entity must already be bound to Form
     *
     * @param  Form        $form
     * @param  array       $data
     * @return Match|false
     */
    public function update(FormInterface $form, array $data, Match $entity)
    {
        $argv = new \ArrayObject;
        $argv['entity'] = $entity;
        $argv['form'] = $form;
        $argv['data'] = $data;

        return $this->processRequest('update', $argv);
    }

    protected function processRequest($action, \ArrayAccess $argv)
    {
        $em = $this->getEventManager();

        // Bind the entity to the form
        $em->attach("{$action}.bind", function (EventInterface $e) {
            $e->getParam('form')->bind($e->getParam('entity'));
        });
        $em->trigger("{$action}.bind", $this, $argv);

        // Populate the form with supplied data
        $em->attach("{$action}.populate", function (EventInterface $e) {
            $e->getParam('form')->setData($e->getParam('data'));
        });
        $em->trigger("{$action}.populate", $this, $argv);

        // Validate the form
        $em->attach("{$action}.validate", function (EventInterface $e) {
            $result = $e->getParam('form')->isValid();
            $e->setParam('result', ($result == true));
        });
        $em->trigger("{$action}.validate", $this, $argv);

        // Extract the validation result
        if ( ! isset($argv['result']) || ! $argv['result'] ) {
            return false;
        }
        $entity = $argv['entity'] = $argv['form']->getData();

        // Persist the new entity
        $em->attach("{$action}.save", function (EventInterface $e) {
            $e->getTarget()->save($e->getParam('entity'));
        });
        $em->trigger("{$action}.save", $this, $argv);

        return $entity;
    }

    public function save(Match $entity)
    {
        $this->getMatchObjectManager()->persist($entity);
        $this->getMatchObjectManager()->flush();
    }

    public function remove(Match $entity)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, ['entity' => $entity]);
        $this->getMatchObjectManager()->remove($entity);
        $this->getMatchObjectManager()->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, ['entity' => $entity]);
    }

    protected function populateTeamEventDataInputDataWithEntityClassNames(&$data)
    {
        $types = $this->getAvailableMatchTeamEventTypes();

        foreach (['H', 'A'] as $type) {
            if ( ! isset($data['match']['teams'][$type]['events']) || count($data['match']['teams'][$type]['events']) == 0 ) {
                continue;
            }
            // Inject the entity class name into the POST request data
            // so that NonuniformCollection knows what entity to create
            foreach ($data['match']['teams'][$type]['events'] as $k=>$v) {
                $key = strtolower($v['event']);
                if ( ! isset($types[$key]) ) {
                    unset($data['match']['teams'][$type]['events'][$k]);
                    continue;
                }
                $data['match']['teams'][$type]['events'][$k]['__class__'] = $types[$key]['entity_class'];
            }
        }
    }

    protected function removeUnusedRosterSlots(&$data)
    {
        if ( !isset($data['match']['teams']) || empty($data['match']['teams']) ) {
            return;
        }
        foreach ($data['match']['teams'] as $team=>&$fsTeam) {
            if ( !isset($fsTeam['players']) || empty($fsTeam['players']) ) {
                continue;
            }
            foreach ($fsTeam['players'] as $pkey=>$pdata) {
                if ( empty($pdata['player']) ) {
                    unset($fsTeam['players'][$pkey]);
                }
            }
        }
    }

    protected function removeExistingSignatures(&$data)
    {
        if ( !isset($data['match']['signatures']) || empty($data['match']['signatures']) ) {
            return;
        }
        foreach ($data['match']['signatures'] as $key => $signature) {
            if ( isset($signature['id']) && !empty($signature['id']) ) {
                unset($data['match']['signatures'][$key]);
            }
        }
    }

    protected function lockMatchIfCompleted(Match $entity)
    {
        if ( $entity->getStatus() == 'F' && $entity->hasSignature('HC') && $entity->hasSignature('AC') && $entity->hasSignature('REF') && $entity->hasSignature('NR4') ) {
            $entity->setIsLocked(true);
        }
    }

    public function setAvailableMatchTeamEventTypes($set)
    {
        $this->availableMatchTeamEventTypes = $set;

        return $this;
    }

    public function getAvailableMatchTeamEventTypes()
    {
        return $this->availableMatchTeamEventTypes;
    }

    public function setCreateForm(FormInterface $f)
    {
        $this->createForm = $f;

        return $this;
    }

    public function getCreateForm()
    {
        return $this->createForm;
    }

    public function setUpdateForm(FormInterface $f)
    {
        $this->updateForm = $f;

        return $this;
    }

    public function getUpdateForm()
    {
        return $this->updateForm;
    }

    public function setMatchRepository(ObjectRepository $or)
    {
        $this->matchRepository = $or;

        return $this;
    }

    public function getMatchRepository()
    {
        return $this->matchRepository;
    }

    public function setMatchObjectManager(ObjectManager $om)
    {
        $this->matchObjectManager = $om;

        return $this;
    }

    public function getMatchObjectManager()
    {
        return $this->matchObjectManager;
    }
}
