<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;

/**
 * Match Team Event Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
abstract class MatchTeamEventFilter extends InputFilter
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
            'name'       => 'minute',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0,
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'event',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Alnum'),
            ),
        ));

    }
}
