<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac;

use Zend\Form\Fieldset;

abstract class RoleAssignmentFieldset extends Fieldset
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'type',
        ));

        $this->get('type')->setValue($name);
    }

    abstract public function getDisplayName();

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),
            'type' => array(
                'required' => true
            ),
        );
    }
}
