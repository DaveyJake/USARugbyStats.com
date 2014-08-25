<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Application\Common\USStates;

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
                'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
                'name' => 'union',
                'options' => array(
                    'label' => 'Union',
                    'object_manager' => $om,
                    'target_class'   => 'UsaRugbyStats\Competition\Entity\Union',
                    'display_empty_item' => true,
                    'empty_item_label'   => 'No Union Specified',
                    'find_method'    => array(
                        'name'   => 'findBy',
                        'params' => array(
                            'criteria' => array(),
                            'orderBy'  => array('name' => 'ASC'),
                        ),
                    ),
                ),
            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'new_logo',
            'options' => array(
                'label' => 'Team Logo',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'new_cover_image',
            'options' => array(
                'label' => 'Cover Image',
            ),
        ));

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

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'city',
            'options' => array(
                'label' => 'City',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'state',
            'options' => array(
                'label' => 'State',
                'empty_option' => 'Please Select a State',
                'value_options' => USStates::$states,
            ),
        ));
    }

}
