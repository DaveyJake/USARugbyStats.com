<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEventFilter;

/**
 * Score Event InputFilter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class ScoreEventFilter extends MatchTeamEventFilter
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name'       => 'type',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Alpha'),
            ),
        ));

        $this->add(array(
            'name'       => 'player',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));
    }
}
