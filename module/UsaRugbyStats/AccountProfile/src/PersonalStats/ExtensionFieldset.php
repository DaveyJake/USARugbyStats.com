<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use Zend\Form\Fieldset;

class ExtensionFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('personalstats');

        $this->add(array(
            'name' => 'height_ft',
            'type' => 'Text',
            'options' => array(
                'label' => 'Height'
            )
        ));

        $this->add(array(
            'name' => 'height_in',
            'type' => 'Text',
            'options' => array(
                'label' => 'Height'
            )
        ));

        $this->add(array(
            'name' => 'weight_lbs',
            'type' => 'Text',
            'options' => array(
                'label' => 'Weight'
            )
        ));

        $this->add(array(
            'name' => 'weight_oz',
            'type' => 'Text',
            'options' => array(
                'label' => 'Weight'
            )
        ));

        $this->add(array(
            'name' => 'benchPress',
            'type' => 'Text',
            'options' => array(
                'label' => 'Bench Press'
            )
        ));

        $this->add(array(
            'name' => 'sprintTime',
            'type' => 'Text',
            'options' => array(
                'label' => 'Sprint Time'
            )
        ));
    }

}
