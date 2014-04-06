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
use UsaRugbyStats\Competition\Entity\Team;

class TeamService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    
    /**
     * Team Repository
     * 
     * @var ObjectRepository
     */
    protected $teamRepository;

    /**
     * Team Object Manager
     *
     * @var ObjectManager
     */
    protected $teamObjectManager;
    
    /**
     * Form used to create new teams
     * 
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing teams
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
        return $this->getTeamRepository()->find($id);
    }
    
    public function fetchAll()
    {
        $adapter = new Selectable($this->getTeamRepository());
        $paginator = new Paginator($adapter);
        return $paginator;
    }
        
    /**
     * Create new team from form data
     * 
     * @param Form $form
     * @param array $data
     * @return Team|false
     */
    public function create(FormInterface $form, array $data)
    {
        $entityClass = $this->getTeamRepository()->getClassName();
    
        $form->bind(new $entityClass);
        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }
        $team = $form->getData();

        $argv = array('entity' => $team, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($team);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
        return $team;
    }
    

    /**
     * Update existing team entity with new data
     *
     * @param Form $form
     * @param array $data
     * @return Team|false
     */
    public function update(FormInterface $form, Team $entity, array $data)
    {
        $form->bind($entity);
        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }
        $team = $form->getData();
    
        $argv = array('entity' => $team, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($team);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
        return $team;
    }
    
    public function save(Team $entity)
    {
        $this->getTeamObjectManager()->persist($entity);
        $this->getTeamObjectManager()->flush();
    }    

    public function remove(Team $entity)
    {
        $this->getTeamObjectManager()->remove($entity);
        $this->getTeamObjectManager()->flush();
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
    
    public function setTeamRepository(ObjectRepository $or)
    {
        $this->teamRepository = $or;
        return $this;
    }
    
    public function getTeamRepository()
    {
        return $this->teamRepository;
    }

    public function setTeamObjectManager(ObjectManager $om)
    {
        $this->teamObjectManager = $om;
        return $this;
    }
    
    public function getTeamObjectManager()
    {
        return $this->teamObjectManager;
    }
}