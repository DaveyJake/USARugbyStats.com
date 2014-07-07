<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use Zend\Form\FormInterface;

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

        $this->addPlayerElements();
    }

    public function __clone()
    {
        parent::__clone();

        $this->remove('player');
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
                        'matchTeam' => $this->getTeam() ? $this->getTeam()->getId() : null,
                    ),
                ),
            ),
        ));
    }

}
