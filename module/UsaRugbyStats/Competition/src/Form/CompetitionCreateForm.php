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

    public function isValid()
    {
        $result = parent::isValid();

        $validData = $this->getInputFilter()->getValidInput()['competition'];
        $fsCompetition = $this->get('competition');

        // Ensure that each division of the competition has a unique name
        $divisionNames = array();
        foreach ( $validData->get('divisions')->getValues() as $key => $arrDivision ) {
            if ( in_array($arrDivision['name'], $divisionNames) ) {
                $fsCompetition->get('divisions')->get($key)->get('name')->setMessages([
                    "There is already a division with this name!"
                ]);
                $result = false;
            }
            array_push($divisionNames, $arrDivision['name']);
        }

        return $result;
    }
}
