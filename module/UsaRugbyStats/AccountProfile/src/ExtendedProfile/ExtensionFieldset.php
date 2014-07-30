<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\Form\Fieldset;

class ExtensionFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('personalstats');

        $this->add(array(
            'name' => 'firstName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Fisrt Name'
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

    }

}
