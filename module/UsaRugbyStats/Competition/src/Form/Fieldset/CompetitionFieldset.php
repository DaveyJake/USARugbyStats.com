<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldset;

class CompetitionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, DivisionFieldset $fsDivision)
    {
        parent::__construct('competition');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Competition Name',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'variant',
            'options' => array(
                'label' => 'Variant',
                'value_options' => [
                    '7s' => 'Rugby 7s',
                    '15s' => 'Rugby 15s',
                ]
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'L' => 'League',
                    'P' => 'Playoffs',
                    'T' => 'Tournament',
                    'F' => 'Friendly',
                ],
                'empty_option' => 'Select a Type',
            ),
        ));

        // @TODO add season instead of date/time

        $this->add(array(
            'type' => 'Zend\Form\Element\Number',
            'name' => 'maxPlayersOnRoster',
            'options' => array(
                'label' => 'Max Players on Roster',
            ),
            'attributes' => array(
                'min'   => 0,
                'max'   => 99,
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'divisions',
            'options' => array(
                'target_element' => $fsDivision,
                'should_create_template' => true,
                'count' => 0,
            )
        ));

    }

}
