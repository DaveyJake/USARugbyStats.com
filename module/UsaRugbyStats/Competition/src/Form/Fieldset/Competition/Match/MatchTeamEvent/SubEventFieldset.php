<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use UsaRugbyStats\Application\Common\ObjectSelect;

class SubEventFieldset extends MatchTeamEventFieldset
{
    protected $objectManager;

    public function __construct(ObjectManager $om)
    {
        parent::__construct('sub', $om);
        $this->objectManager = $om;

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
            'type' => 'Zend\Form\Element\Text',
            'name' => 'playerOn',
            'options' => array(
                'label' => 'Player On',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'playerOff',
            'options' => array(
                'label' => 'Player Off',
            ),
        ));

    }

    public function filterPlayerSelectForTeam($teamid)
    {
        $findMethod = [
            'name'   => 'findAllPlayersForMatchTeam',
            'params' => [ 'matchTeam' => $teamid ],
        ];

        foreach (['playerOn' => 'Player On', 'playerOff' => 'Player Off'] as $field =>$label) {
            if ( ! $this->get($field) instanceof ObjectSelect ) {
                $value = $this->get($field)->getValue();

                $this->remove($field);
                $this->add(array(
                    'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
                    'name' => $field,
                    'options' => array(
                        'label' => $label,
                        'object_manager' => $this->objectManager,
                        'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
                        'find_method'    => $findMethod,
                    ),
                ));

                $this->get($field)->setValue($value);
            }
            $this->get($field)->setFindMethod($findMethod);
        }
    }

}
