<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset;
use Zend\Form\FormInterface;

class SubEventFieldset extends MatchTeamEventFieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('sub', $om);

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

        $this->addPlayerElements();
    }

    public function prepareElement(FormInterface $form)
    {
        if ($this->getTeam()) {
            $this->remove('playerOn');
            $this->remove('playerOff');
            $this->addPlayerElements();
        }

        return parent::prepareElement($form);
    }

    protected function addPlayerElements()
    {
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'playerOn',
            'options' => array(
                'label' => 'Player On',
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

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'playerOff',
            'options' => array(
                'label' => 'Player Off',
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
