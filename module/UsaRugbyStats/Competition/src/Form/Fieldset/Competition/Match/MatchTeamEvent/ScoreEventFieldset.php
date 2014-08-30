<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use UsaRugbyStats\Application\Common\ObjectSelect;

class ScoreEventFieldset extends MatchTeamEventFieldset
{
    protected $objectManager;

    public function __construct(ObjectManager $om)
    {
        parent::__construct('score', $om);
        $this->objectManager = $om;

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'CV' => 'Conversion',
                    'DG' => 'Drop Goal',
                    'PK' => 'Penalty Kick',
                    'PT' => 'Penalty Try',
                    'TR' => 'Try',
                ],
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'player',
            'options' => array(
                'label' => 'Player',
            ),
        ));

    }

    public function filterPlayerSelectForTeam($teamid)
    {
        $findMethod = [
            'name'   => 'findAllPlayersForMatchTeam',
            'params' => [ 'matchTeam' => $teamid ],
        ];

        if ( ! $this->get('player') instanceof ObjectSelect ) {
            $value = $this->get('player')->getValue();

            $this->remove('player');
            $this->add(array(
                'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
                'name' => 'player',
                'options' => array(
                    'label' => 'Player',
                    'object_manager' => $this->objectManager,
                    'display_empty_item' => true,
                    'empty_option'   => 'No Player Selected (Team Score)',
                    'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
                    'find_method'    => $findMethod,
                ),
            ));

            $this->get('player')->setValue($value);
        }
        $this->get('player')->setFindMethod($findMethod);
    }

}
