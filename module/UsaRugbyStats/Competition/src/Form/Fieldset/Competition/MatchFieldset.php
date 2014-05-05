<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MatchFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, Match\MatchTeamFieldset $fsHomeTeam, Match\MatchTeamFieldset $fsAwayTeam, Match\MatchSignatureFieldset $fsSignature)
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
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'competition',
            'options' => array(
                'label' => 'Competition',
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
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'teams',
            'options' => array(
                'target_element' => $fsHomeTeam,
                'should_create_template' => true,
                'template_placeholder' => '__sigindex__',
                'count' => 2,
            )
        ));

        $fsHomeTeam->setName('H');
        $fsHomeTeam->get('type')->setValue('H');
        $this->get('teams')->add($fsHomeTeam);

        $fsAwayTeam->setName('A');
        $fsAwayTeam->get('type')->setValue('A');
        $this->get('teams')->add($fsAwayTeam);

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

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'signatures',
            'options' => array(
                'target_element' => $fsSignature,
                'should_create_template' => true,
                'template_placeholder' => '__sigindex__',
                'count' => 0,
            )
        ));

    }

}
