<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\Form\Fieldset;

class ExtensionFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('extprofile');

        $this->add(array(
            'name' => 'firstName',
            'type' => 'Text',
            'options' => array(
                'label' => 'First Name'
            )
        ));

        $this->add(array(
            'name' => 'lastName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Last Name'
            )
        ));

        $this->add(array(
            'name' => 'telephoneNumber',
            'type' => 'Text',
            'options' => array(
                'label' => 'Telephone Number'
            )
        ));

        $this->add(array(
            'name' => 'photoSource',
            'type' => 'Radio',
            'options' => array(
                'label' => 'Profile Picture',
                'value_options' => array(
                    'G' => 'Gravatar',
                    'C' => 'Custom Photo',
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'custom_photo',
            'options' => array(
                'label' => 'Choose new File',
            ),
        ));
    }

}
