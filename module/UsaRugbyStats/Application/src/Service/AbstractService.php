<?php
namespace UsaRugbyStats\Application\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;
use Zend\Form\FormInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;

abstract class AbstractService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Repository
     *
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * Object Manager
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Form used to create new instances
     *
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing instances
     *
     * @var FormInterface
     */
    protected $updateForm;

    /**
     * FQCN of the entity class
     *
     * @var string
     */
    protected $entityClassName;

    /**
     * Additional EventManager identifiers
     *
     * @var array
     */
    protected $eventIdentifier = array();

    public function __construct()
    {
        array_push($this->eventIdentifier, get_called_class());
    }

    public function findByID($id)
    {
        $id = StaticFilter::execute($id, 'Digits');
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->find($id);
    }

    public function fetchAll()
    {
        $adapter = new Selectable($this->getRepository());
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Create new entity from form data
     *
     * @param  array         $data
     * @return stdClass|NULL
     */
    public function create(array $data)
    {
        $entityClass = $this->getEntityClassName();

        $argv = new \ArrayObject();
        $argv->entity = new $entityClass;
        $argv->form   = $this->getCreateForm();
        $argv->data   = $data;

        return $this->processRequest($argv);
    }

    /**
     * Update existing entity with new data
     *
     * @param  stdClass                 $entity
     * @param  array                    $data
     * @return stdClass|NULL
     * @throws InvalidArgumentException when $entity isn't of same type as repository entity
     */
    public function update($entity, array $data)
    {
        $entityClass = $this->getEntityClassName();
        if (! $entity instanceof $entityClass) {
            throw new \InvalidArgumentException(sprintf(
                "Expected entity of class '%s'; received '%s'",
                $entityClass,
                get_class($entity)
            ));
        }

        $argv = new \ArrayObject();
        $argv->form   = $this->getUpdateForm();
        $argv->entity = $entity;
        $argv->data   = $data;

        return $this->processRequest($argv);
    }

    protected function processRequest(\ArrayAccess $argv)
    {
        // Bind entity
        $this->getEventManager()->trigger("form.bind", $this, $argv);
        $argv->form->bind($argv->entity);
        $this->getEventManager()->trigger("form.bind.post", $this, $argv);

        // Stick in the data
        $this->getEventManager()->trigger("form.populate", $this, $argv);
        $argv->form->setData($argv->data);
        $this->getEventManager()->trigger("form.populate.post", $this, $argv);

        // Validate it
        $this->getEventManager()->trigger("form.validate", $this, $argv);
        $argv->result = $argv->form->isValid();
        $this->getEventManager()->trigger("form.validate.post", $this, $argv);
        if (! $argv->result) {
            return false;
        }

        // Pull out the resulting entity
        $argv->entity = $argv->form->getData();

        // Hand off to the persistence layer
        $this->save($argv->entity);

        return $argv->entity;
    }

    public function save($entity)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, ['entity' => $entity]);
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, ['entity' => $entity]);
    }

    public function remove($entity)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, ['entity' => $entity]);
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();
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

    public function setRepository(ObjectRepository $or)
    {
        $this->repository = $or;

        return $this;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setObjectManager(ObjectManager $om)
    {
        $this->objectManager = $om;

        return $this;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @return string
     */
    public function getEntityClassName()
    {
        if ( empty($this->entityClassName) ) {
            $this->entityClassName = $this->getRepository()->getClassName();
        }

        return $this->entityClassName;
    }

    /**
     * @param string $fqcn
     */
    public function setEntityClassName($fqcn)
    {
        $this->entityClassName = $fqcn;

        return $this;
    }

}
