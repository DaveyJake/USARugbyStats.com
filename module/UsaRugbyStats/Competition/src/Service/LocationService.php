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
use UsaRugbyStats\Competition\Entity\Location;

class LocationService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Location Repository
     *
     * @var ObjectRepository
     */
    protected $locationRepository;

    /**
     * Location Object Manager
     *
     * @var ObjectManager
     */
    protected $locationObjectManager;

    /**
     * Form used to create new locations
     *
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing locations
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

        return $this->getLocationRepository()->find($id);
    }

    public function fetchAll()
    {
        $adapter = new Selectable($this->getLocationRepository());
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Create new location from form data
     *
     * @param  Form           $form
     * @param  array          $data
     * @return Location|false
     */
    public function create(FormInterface $form, array $data)
    {
        $entityClass = $this->getLocationRepository()->getClassName();

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
     * Update existing location entity with new data
     *
     * @precondition Entity must already be bound to Form
     *
     * @param  Form           $form
     * @param  array          $data
     * @return Location|false
     */
    public function update(FormInterface $form, array $data)
    {
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

    public function save(Location $entity)
    {
        $this->getLocationObjectManager()->persist($entity);
        $this->getLocationObjectManager()->flush();
    }

    public function remove(Location $entity)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, ['entity' => $entity]);
        $this->getLocationObjectManager()->remove($entity);
        $this->getLocationObjectManager()->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, ['entity' => $entity]);
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

    public function setLocationRepository(ObjectRepository $or)
    {
        $this->locationRepository = $or;

        return $this;
    }

    public function getLocationRepository()
    {
        return $this->locationRepository;
    }

    public function setLocationObjectManager(ObjectManager $om)
    {
        $this->locationObjectManager = $om;

        return $this;
    }

    public function getLocationObjectManager()
    {
        return $this->locationObjectManager;
    }
}
