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

        $validInput = $this->getInputFilter()->getValidInput();
        if ( ! isset($validInput['competition']) ) {
            return $result;
        }

        $validData = $validInput['competition'];
        $fsCompetition = $this->get('competition');

        // Ensure that each division of the competition has a unique name
        $divisionNames = array();
        $teams = array();
        foreach ( $validData->get('divisions')->getValues() as $divKey => $arrDivision ) {
            if ( in_array($arrDivision['name'], $divisionNames) ) {
                $fsCompetition->get('divisions')->get($divKey)->get('name')->setMessages([
                    "There is already a division with this name!"
                ]);
                $result = false;
            }
            array_push($divisionNames, $arrDivision['name']);

            // Ensure that the teams it contains are unique to the entire competition
            if ( ! isset($arrDivision['teamMemberships']) || ! is_array($arrDivision['teamMemberships']) ) {
                continue;
            }
            foreach ($arrDivision['teamMemberships'] as $teamKey => $arrTeamMembership) {
                if ( in_array($arrTeamMembership['team'], $teams) ) {
                    $fsCompetition->get('divisions')->get($divKey)->get('teamMemberships')->get($teamKey)->setMessages([
                        "This team has already been added to another division!"
                    ]);
                    $result = false;
                }
                array_push($teams, $arrTeamMembership['team']);
            }
        }

        return $result;
    }
}
