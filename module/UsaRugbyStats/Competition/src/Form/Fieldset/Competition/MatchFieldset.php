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
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'location',
            'options' => array(
                'label' => 'Location',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Location',
                'display_empty_item' => true,
                'empty_item_label'   => 'No Location Specified',
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'locationDetails',
            'options' => array(
                'label' => 'Location Details',
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'teams',
            'options' => array(
                'target_element' => $fsHomeTeam,
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

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isLocked',
            'options' => array(
                'label' => 'Is Locked?',
                'value_options' => array(
                    1 => 'Yes',
                    0 => 'No',
                ),
            ),
        ));
    }

}
