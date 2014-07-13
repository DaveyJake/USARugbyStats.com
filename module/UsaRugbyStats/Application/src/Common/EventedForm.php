<?php
namespace UsaRugbyStats\Application\Common;

use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class EventedForm extends Form implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    protected $eventIdentifier = 'Zend\Form\Form';

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::add()
     */
    public function add($elementOrFieldset, array $flags = array())
    {
        $argv = new \ArrayObject();
        $argv->elementOrFieldset = $elementOrFieldset;
        $argv->flags = $flags;

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->result = parent::add($argv->elementOrFieldset, $argv->flags);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->result;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::bind()
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        $argv = new \ArrayObject();
        $argv->object = $object;
        $argv->flags = $flags;

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->result = parent::bind($argv->object, $argv->flags);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->result;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::getData()
     */
    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
        $argv = new \ArrayObject();
        $argv->flag = $flag;

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->data = parent::getData($argv->flag);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->data;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::getInputFilter()
     */
    public function getInputFilter()
    {
        $argv = new \ArrayObject();

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->inputFilter = parent::getInputFilter();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->inputFilter;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::getValidationGroup()
     */
    public function getValidationGroup()
    {
        $argv = new \ArrayObject();

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->validationGroup = parent::getValidationGroup();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->validationGroup;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::isValid()
     */
    public function isValid()
    {
        $argv = new \ArrayObject();

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->result = parent::isValid();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->result;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::prepare()
     */
    public function prepare()
    {
        $argv = new \ArrayObject();

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->result = parent::prepare();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);

        return $argv->result;
    }

    /* (non-PHPdoc)
     * @see \Zend\Form\Form::setData()
     */
    public function setData($data)
    {
        $argv = new \ArrayObject();
        $argv->data = $data;

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $argv->result = parent::setData($argv->data);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this,  $argv);

        return $argv->result;
    }

}
