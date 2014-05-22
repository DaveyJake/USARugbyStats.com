<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use Zend\Form\FormInterface;

class ScoreEventFieldset extends MatchTeamEventFieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('score', $om);

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

        $this->addPlayerElements();
    }

    public function prepareElement(FormInterface $form)
    {
        if ($this->getTeam()) {
            $value = $this->get('player')->getValue();
            $this->remove('player');
            $this->addPlayerElements();
            $this->get('player')->setValue($value);
        }

        return parent::prepareElement($form);
    }

    protected function addPlayerElements()
    {
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'player',
            'options' => array(
                'label' => 'Player',
                'object_manager' => $this->getObjectManager(),
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findAllPlayersForMatchTeam',
                    'params' => array(
                        'matchTeam' => $this->getTeam() ? $this->getTeam()->getId() : NULL,
                    ),
                ),
            ),
        ));
    }
}
