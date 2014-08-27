<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;

class CardEventFieldset extends MatchTeamEventFieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('card', $om);

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'R' => 'Red',
                    'Y' => 'Yellow',
                ],
            ),
        ));

        $this->add(array(
            'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
            'name' => 'player',
            'options' => array(
                'label' => 'Player',
                'object_manager' => $om,
                'empty_option'   => 'No Player Selected (Team Card)',
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
            ),
        ));
    }

}
