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

    public function __clone()
    {
        parent::__clone();

        $this->remove('playerOn');
        $this->remove('playerOff');
        $this->addPlayerElements();
    }

    public function prepareElement(FormInterface $form)
    {
        if ($this->getTeam()) {
            $pon = $this->get('playerOn')->getValue();
            $poff = $this->get('playerOff')->getValue();
            $this->remove('playerOn');
            $this->remove('playerOff');
            $this->addPlayerElements();
            $this->get('playerOn')->setValue($pon);
            $this->get('playerOff')->setValue($poff);
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
                        'matchTeam' => $this->getTeam() ? $this->getTeam()->getId() : null,
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
                        'matchTeam' => $this->getTeam() ? $this->getTeam()->getId() : null,
                    ),
                ),
            ),
        ));
    }

}
