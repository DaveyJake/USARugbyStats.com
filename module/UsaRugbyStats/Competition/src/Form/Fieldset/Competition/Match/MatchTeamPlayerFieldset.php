<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Entity\Competition;

class MatchTeamPlayerFieldset extends Fieldset
{
    protected $objectManager;

    public static $positions = [
        Competition::VARIANT_FIFTEENS => [
            'LHP' => 'Loose-Head Prop (P)',
            'H' => 'Hooker (H)',
            'THP' => 'Tight-Head Prop (P)',
            'L1' => 'Lock 1 (L)',
            'L2' => 'Lock 2 (L)',
            'BSF' => 'Blind Side Flanker (F)',
            'OSF' => 'Open Side Flanker (F)',
            'N8' => 'Number 8 (N8)',
            'SH' => 'Scrum Half (SH)',
            'FH' => 'Fly Half (FH)',
            'W1' => 'Wing 1 (W)',
            'IC' => 'Inside Center (C)',
            'OC' => 'Outside Center (C)',
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
        ],
        Competition::VARIANT_SEVENS => [
            '7P1' => 'Prop (P)',
            'H' => 'Hooker (H)',
            '7P2' => 'Prop (P)',
            'SH' => 'Scrum Half (SH)',
            'FH' => 'Fly Half (FH)',
            '7C' => 'Center (C)',
            '7WF' => 'Wing',
            'R1' => 'Reserve 1 (R)',
            'R2' => 'Reserve 2 (R)',
            'R3' => 'Reserve 3 (R)',
            'R4' => 'Reserve 4 (R)',
            'R5' => 'Reserve 5 (R)',
            'R6' => 'Reserve 6 (R)',
            'R7' => 'Reserve 7 (R)',
            'R8' => 'Reserve 8 (R)',
        ],
    ];

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
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'team',
            'options' => array(
                'label' => 'Team',
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
                'value_options' => []
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

        $this->add(array(
            'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
            'name' => 'player',
            'options' => array(
                'label' => 'Player',
                'object_manager' => $this->objectManager,
                'target_class'   => 'UsaRugbyStats\Account\Entity\Account',
                'display_empty_item' => true,
                'empty_item_label'   => 'Select a Player',
            ),
        ));
    }

    public function setVariant($v)
    {
        if ($v != Competition::VARIANT_FIFTEENS && $v != Competition::VARIANT_SEVENS) {
            return false;
        }

        $this->get('position')->setValueOptions(self::$positions[$v]);
    }
}
