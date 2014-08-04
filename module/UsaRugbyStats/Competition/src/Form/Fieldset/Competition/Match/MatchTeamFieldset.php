<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\FieldsetInterface;
use Zend\Form\Element\Collection;
use Zend\Form\FormInterface;

class MatchTeamFieldset extends Fieldset
{
    protected $objectManager;

    public function __construct(ObjectManager $om, FieldsetInterface $fsMatchTeamPlayer, Collection $collEvents)
    {
        parent::__construct('match-team');

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
            'name' => 'score',
            'options' => array(
                'label' => 'Score',
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
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findAllTeamsInCompetition',
                    'params' => array(
                        'competition' => NULL,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'players',
            'options' => array(
                'target_element' => $fsMatchTeamPlayer,
                'should_create_template' => true,
                'template_placeholder' => '__playerindex__',
                'count' => 0,
            )
        ));

        $this->add($collEvents);

    }

    public function prepareElement(FormInterface $form)
    {
        $selectedTeam = null;

        // If a competition ID is provided, filter list of teams by it
        if ($form->has('match') && $form->get('match')->has('competition')) {
            $competition = $form->get('match')->get('competition')->getValue();
            if ( !empty($competition) ) {
                $selectedTeam = $this->get('team')->getValue();

                $this->remove('team');
                $this->add(array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'team',
                    'options' => array(
                        'label' => 'Team',
                        'object_manager' => $this->objectManager,
                        'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
                        'is_method'      => true,
                        'find_method'    => array(
                            'name'   => 'findAllTeamsInCompetition',
                            'params' => array(
                                'competition' => is_object($competition) ? $competition->getId() : (int) $competition,
                            ),
                        ),
                    ),
                ));
                $this->get('team')->setValue($selectedTeam);

                $fsTeamPlayers = $this->get('players')->getTargetElement();
                $fsTeamPlayers->addPlayerSelect($selectedTeam);
            }
        }

        if ( $this->get('id')->getValue() ) {
            $types = $this->get('events')->getTargetElement();
            foreach ($types as $type) {
                $type->setTeam($this->getObject());
            }
        }

        return parent::prepareElement($form);
    }

}
