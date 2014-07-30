<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\InputFilter\InputFilter;

class ExtensionInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'firstName',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));

        $this->add(array(
            'name'       => 'lastName',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));

        $this->add(array(
            'name'       => 'telephoneNumber',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(),
        ));
    }
}
