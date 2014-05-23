<?php
namespace UsaRugbyStats\Competition\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;
use Zend\Form\FormInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Competition Repository
     *
     * @var ObjectRepository
     */
    protected $competitionRepository;

    /**
     * Competition Object Manager
     *
     * @var ObjectManager
     */
    protected $competitionObjectManager;

    /**
     * Form used to create new competitions
     *
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing competitions
     *
     * @var FormInterface
     */
    protected $updateForm;

    public function findByID($id)
    {
        $id = StaticFilter::execute($id, 'Digits');
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getCompetitionRepository()->find($id);
    }

    public function fetchAll()
    {
        $adapter = new Selectable($this->getCompetitionRepository());
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Create new competition from form data
     *
     * @param  Form              $form
     * @param  array             $data
     * @return Competition|false
     */
    public function create(FormInterface $form, array $data)
    {
        $entityClass = $this->getCompetitionRepository()->getClassName();

        $entity = new $entityClass;
        $form->bind($entity);

        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }

        $argv = array('entity' => $entity, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($entity);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $entity;
    }

    /**
     * Update existing competition entity with new data
     *
     * @precondition Entity must already be bound to Form
     *
     * @param  Form              $form
     * @param  array             $data
     * @return Competition|false
     */
    public function update(FormInterface $form, array $data, Competition $entity)
    {
        // @HACK to fix GH-15 (Can't empty an existing Collection)
        if ( !isset($data['competition']['divisions']) ) {
            $data['competition']['divisions'] = array();
        }
        if ( count($data['competition']['divisions']) ) {
            $mapping = array_column($data['competition']['divisions'], 'id');
            foreach ( $entity->getDivisions() as $division ) {
                $index = array_search($division->getId(), $mapping);
                if ($index === false) {
                    continue;
                }
                if ( ! isset($data['competition']['divisions'][$index]['teamMemberships']) || empty($data['competition']['divisions'][$index]['teamMemberships']) ) {
                    $division->removeTeamMemberships($division->getTeamMemberships());
                }
            }
        }

        $form->bind($entity);
        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }
        $entity = $form->getData();

        $argv = array('entity' => $entity, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($entity);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $entity;
    }

    public function save(Competition $entity)
    {
        $this->getCompetitionObjectManager()->persist($entity);
        $this->getCompetitionObjectManager()->flush();
    }

    public function remove(Competition $entity)
    {
        $this->getCompetitionObjectManager()->remove($entity);
        $this->getCompetitionObjectManager()->flush();
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

    public function setCompetitionRepository(ObjectRepository $or)
    {
        $this->competitionRepository = $or;

        return $this;
    }

    public function getCompetitionRepository()
    {
        return $this->competitionRepository;
    }

    public function setCompetitionObjectManager(ObjectManager $om)
    {
        $this->competitionObjectManager = $om;

        return $this;
    }

    public function getCompetitionObjectManager()
    {
        return $this->competitionObjectManager;
    }
}
