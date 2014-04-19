<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MatchFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('match');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'description',
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\DateTime',
            'name' => 'date',
            'options' => array(
                'label' => 'Date & Time',
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'homeTeam',
            'options' => array(
                'label' => 'Home Team',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'awayTeam',
            'options' => array(
                'label' => 'Away Team',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'status',
            'options' => array(
                'label' => 'Status',
                'value_options' => [
                    'NS' => 'Not Yet Started',
                    'S' => 'Started',
                    'F' => 'Finished',
                    'HF' => 'Home Forfeit',
                    'AF' => 'Away Forfeit',
                    'C' => 'Cancelled',
                ]
            ),
        ));
    }

}
