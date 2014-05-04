<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\FieldsetInterface;
use Zend\Form\Element\Collection;

class MatchTeamFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, FieldsetInterface $fsMatchTeamPlayer, Collection $collEvents)
    {
        parent::__construct('match-team');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'H' => 'Home Team',
                    'A' => 'Away Team',
                ]
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'team',
            'options' => array(
                'label' => 'Team',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'players',
            'options' => array(
                'target_element' => $fsMatchTeamPlayer,
                'should_create_template' => true,
                'template_placeholder' => '__playerindex__',
            )
        ));

        $this->add($collEvents);

    }

}
