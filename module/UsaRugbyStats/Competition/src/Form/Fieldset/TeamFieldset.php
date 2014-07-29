<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('team');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'remoteId',
            'options' => array(
                'label' => 'Remote ID',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Team Name',
            ),
        ));

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'union',
                'options' => array(
                    'label' => 'Union',
                    'object_manager' => $om,
                    'target_class'   => 'UsaRugbyStats\Competition\Entity\Union',
                    'display_empty_item' => true,
                    'empty_item_label'   => 'No Union Specified',
                ),
            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'email',
            'options' => array(
                'label' => 'Email Address',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'website',
            'options' => array(
                'label' => 'Team Website',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'facebookHandle',
            'options' => array(
                'label' => 'Facebook Handle',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'twitterHandle',
            'options' => array(
                'label' => 'Twitter Handle',
            ),
        ));

    }

}
