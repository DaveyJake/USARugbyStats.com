<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use Zend\InputFilter\InputFilter;

class ExtensionInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'height_ft',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Int'),
                array('name' => 'Between', 'options' => array('min' => 1, 'max' => '10'))
            ),
        ));

        $this->add(array(
            'name'       => 'height_in',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Int'),
                array('name' => 'Between', 'options' => array('min' => 0, 'max' => '11'))
            ),
        ));

        $this->add(array(
            'name'       => 'weight_lbs',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Int'),
                array('name' => 'Between', 'options' => array('min' => 1, 'max' => '500'))
            ),
        ));

        $this->add(array(
            'name'       => 'weight_oz',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Int'),
                array('name' => 'Between', 'options' => array('min' => 0, 'max' => '15'))
            ),
        ));

        $this->add(array(
            'name'       => 'benchPress',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Float'),
                array('name' => 'Between', 'options' => array('min' => 1, 'max' => '500'))
            ),
        ));

        $this->add(array(
            'name'       => 'sprintTime',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array('name' => 'Float'),
                array('name' => 'Between', 'options' => array('min' => 1, 'max' => '500'))
            ),
        ));
    }
}
