<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;

/**
 * Competition Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchFilter extends InputFilter
{
    public function __construct()
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'description',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

    }
}
