<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MatchFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, Match\MatchTeamFieldset $fsHomeTeam, Match\MatchTeamFieldset $fsAwayTeam)
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

        $fsHomeTeam->setName('homeTeam');
        $fsHomeTeam->get('type')->setValue('H');
        $this->add($fsHomeTeam);

        $fsAwayTeam->setName('awayTeam');
        $fsAwayTeam->get('type')->setValue('A');
        $this->add($fsAwayTeam);

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
