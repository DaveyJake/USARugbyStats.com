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
use UsaRugbyStats\Application\Common\ExtendedValidationGroupForm;
use UsaRugbyStats\Application\FeatureFlags\FeatureFlags;
use Zend\Stdlib\CallbackHandler;
use Zend\EventManager\EventInterface;
use Doctrine\Common\Collections\Criteria;

abstract class AbstractService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Session Context
     *
     * @var \ArrayAccess
     */
    protected $session;

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
     * Service Extension Manager
     *
     * @var ServiceExtensionManager
     */
    protected $extensionManager;

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

        if ( is_null($this->session) ) {
            $this->startSession();
        }
    }

    public function findByID($id)
    {
        $id = StaticFilter::execute($id, 'Digits');
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->find($id);
    }

    public function fetchAll(Criteria $c = null)
    {
        $adapter = new Selectable($this->getRepository(), $c);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Prepare Form for use in CRUD controller
     *
     * @param \ArrayAccess $context
     */
    public function startSession()
    {
        $this->session = new \ArrayObject();

        if ( class_exists('UsaRugbyStats\Application\FeatureFlags\FeatureFlags', true) ) {
            $this->session->flags = new FeatureFlags();
        }

        return $this->session;
    }

    public function prepare()
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, $this->session);

        // Bind entity
        $this->getEventManager()->trigger("form.bind", $this, $this->session);
        $this->session->form->bind($this->session->entity);
        if ($this->session->form instanceof ExtendedValidationGroupForm) {
            $this->session->form->generateValidationGroup();
        }
        $this->getEventManager()->trigger("form.bind.post", $this, $this->session);

        $this->session->{'__isPrepared'} = true;

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, $this->session);

        return $this;
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

        if ( !isset($this->session->entity) ) {
            $this->session->entity = new $entityClass();
        }
        if ( !isset($this->session->form) ) {
            $this->session->form = $this->getCreateForm();
        }
        $this->session->data = $data;

        return $this->processRequest($this->session);
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

        if ( !isset($this->session->form) ) {
            $this->session->form = $this->getUpdateForm();
        }
        $this->session->entity = $entity;
        $this->session->data = $data;

        return $this->processRequest($this->session);
    }

    protected function processRequest(\ArrayAccess $argv)
    {
        if (! $argv->{'__isPrepared'}) {
            throw new \RuntimeException(get_called_class() . '::prepare() has not been called!');
        }

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
        $argv = new \ArrayObject();
        $argv->entity = $entity;

        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
    }

    public function remove($entity)
    {
        $argv = new \ArrayObject();
        $argv->entity = $entity;

        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
    }

    /**
     * @return ServiceExtensionManager
     */
    public function getExtensionManager()
    {
        return $this->extensionManager;
    }

    /**
     * @param ServiceExtensionManager $extensionManager
     */
    public function setExtensionManager(ServiceExtensionManager $extensionManager)
    {
        if ($this->extensionManager instanceof ServiceExtensionManager) {
            $this->extensionManager->detachEventListeners($this->getEventManager());
        }

        $this->extensionManager = $extensionManager;
        $this->extensionManager->attachEventListeners($this->getEventManager());

        return $this;
    }

    public function setCreateForm(FormInterface $f)
    {
        $this->createForm = $f;
        $this->attachFlagInjectorFormListener($this->createForm);

        return $this;
    }

    public function getCreateForm()
    {
        return $this->createForm;
    }

    public function setUpdateForm(FormInterface $f)
    {
        $this->updateForm = $f;
        $this->attachFlagInjectorFormListener($this->updateForm);

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

    protected $flagInjectorFormListener;
    protected function attachFlagInjectorFormListener(FormInterface $f)
    {
        if (! $f instanceof ExtendedValidationGroupForm) {
            return;
        }

        if ( empty($this->flagInjectorFormListener) ) {
            $service = $this;
            $this->flagInjectorFormListener = new CallbackHandler(function (EventInterface $e) use ($service) {
                if (! $service->session->flags instanceof FeatureFlags) {
                    return;
                }
                $e->getTarget()->setFeatureFlags($service->session->flags);
            });
        }

        $f->getEventManager()->detach($this->flagInjectorFormListener);
        $f->getEventManager()->attach('prepareValidationGroup.pre', $this->flagInjectorFormListener);
        $f->getEventManager()->attach('autogenerateValidationGroupForForm.pre', $this->flagInjectorFormListener);
    }

}
