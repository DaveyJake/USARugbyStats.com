<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;

class SubEventFieldset extends MatchTeamEventFieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('sub', $om);

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'BL' => 'Blood',
                    'IJ' => 'Injury',
                    'FRC' => 'Front Row Card',
                    'TC' => 'Tactical',
                ],
            ),
        ));

        $this->add(array(
            'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
            'name' => 'playerOn',
            'options' => array(
                'label' => 'Player On',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
            ),
        ));

        $this->add(array(
            'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
            'name' => 'playerOff',
            'options' => array(
                'label' => 'Player Off',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
            ),
        ));
    }

}
