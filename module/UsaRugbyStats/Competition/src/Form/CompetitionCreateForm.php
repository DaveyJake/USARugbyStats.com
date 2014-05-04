<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\Form\Form;
use Zend\Form\FieldsetInterface;

class CompetitionCreateForm extends Form
{
    public function __construct(FieldsetInterface $competitionFieldset)
    {
        parent::__construct('create-competition');

        // Set the base fieldset (competition)
        $competitionFieldset->setUseAsBaseFieldset(true);
        $this->add($competitionFieldset);

        // Add the submit button
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Competition',
            ),
        ));
    }
}