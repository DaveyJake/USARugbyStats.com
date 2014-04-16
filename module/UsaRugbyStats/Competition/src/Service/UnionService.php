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
use UsaRugbyStats\Competition\Entity\Union;

class UnionService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Union Repository
     *
     * @var ObjectRepository
     */
    protected $unionRepository;

    /**
     * Union Object Manager
     *
     * @var ObjectManager
     */
    protected $unionObjectManager;

    /**
     * Form used to create new unions
     *
     * @var FormInterface
     */
    protected $createForm;

    /**
     * Form used to update existing unions
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

        return $this->getUnionRepository()->find($id);
    }

    public function fetchAll()
    {
        $adapter = new Selectable($this->getUnionRepository());
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * Create new union from form data
     *
     * @param  Form        $form
     * @param  array       $data
     * @return Union|false
     */
    public function create(FormInterface $form, array $data)
    {
        $entityClass = $this->getUnionRepository()->getClassName();

        $form->bind(new $entityClass);
        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }
        $union = $form->getData();

        $argv = array('entity' => $union, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($union);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $union;
    }

    /**
     * Update existing union entity with new data
     *
     * @param  Form        $form
     * @param  array       $data
     * @return Union|false
     */
    public function update(FormInterface $form, Union $entity, array $data)
    {
        // Only bind the entity if it's not already bound
        if ( ! $form->getObject() === $entity ) {
            $form->bind($entity);
        }
        
        $form->setData($data);
        if ( ! $form->isValid() ) {
            return false;
        }
        $union = $form->getData();

        $argv = array('entity' => $union, 'form' => $form, 'data' => $data);
        $this->getEventManager()->trigger(__FUNCTION__, $this, $argv);
        $this->save($union);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $union;
    }

    public function save(Union $entity)
    {
        $this->getUnionObjectManager()->persist($entity);
        $this->getUnionObjectManager()->flush();
    }

    public function remove(Union $entity)
    {
        $this->getUnionObjectManager()->remove($entity);
        $this->getUnionObjectManager()->flush();
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

    public function setUnionRepository(ObjectRepository $or)
    {
        $this->unionRepository = $or;

        return $this;
    }

    public function getUnionRepository()
    {
        return $this->unionRepository;
    }

    public function setUnionObjectManager(ObjectManager $om)
    {
        $this->unionObjectManager = $om;

        return $this;
    }

    public function getUnionObjectManager()
    {
        return $this->unionObjectManager;
    }
}
