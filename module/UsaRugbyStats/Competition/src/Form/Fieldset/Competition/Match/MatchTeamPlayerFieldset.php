<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Entity\Team;

class MatchTeamPlayerFieldset extends Fieldset
{
    protected $objectManager;

    public function __construct(ObjectManager $om)
    {
        parent::__construct('team-player');

        $this->objectManager = $om;

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'number',
            'options' => array(
                'label' => 'Type',
                'value_options' => range(0,99),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'position',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'LHP' => 'Loose-Head Prop (P)',
                    'H' => 'Hooker (H)',
                    'THP' => 'Tight-Head Prop (P)',
                    'L1' => 'Lock 1 (L)',
                    'L2' => 'Lock 2 (L)',
                    'OSF' => 'Open Side Flanker (F)',
                    'BSF' => 'Blind Side Flanker (F)',
                    'N8' => 'Number 8 (N8)',
                    'SH' => 'Scrum Half (SH)',
                    'FH' => 'Fly Half (FH)',
                    'IC' => 'Inside Center (C)',
                    'OC' => 'Outside Center (C)',
                    'W1' => 'Wing 1 (W)',
                    'W2' => 'Wing 2 (W)',
                    'FB' => 'Fullback (FB)',
                    'R1' => 'Reserve 1 (R)',
                    'R2' => 'Reserve 2 (R)',
                    'R3' => 'Reserve 3 (R)',
                    'R4' => 'Reserve 4 (R)',
                    'R5' => 'Reserve 5 (R)',
                    'R6' => 'Reserve 6 (R)',
                    'R7' => 'Reserve 7 (R)',
                    'R8' => 'Reserve 8 (R)',
                ]
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'isFrontRow',
            'options' => array(
                'label' => 'Type',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
        ));

        $this->addPlayerSelect(null);
    }

    public function addPlayerSelect($t = null)
    {
        $selectedValue = null;

        if ( $this->has('player') ) {
            $selectedValue = $this->get('player')->getValue();
            $this->remove('player');
        }

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'player',
            'options' => array(
                'label' => 'Player',
                'object_manager' => $this->objectManager,
                'target_class'   => 'UsaRugbyStats\Account\Entity\Account',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findAllCurrentMembersForTeam',
                    'params' => array(
                        'team' => $t,
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Select a Player',
            ),
        ));
        $this->get('player')->setValue($selectedValue);
    }
}
