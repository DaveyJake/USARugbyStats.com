<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilterInterface;
use UsaRugbyStats\Application\Common\NestedCollectionInputFilter;

/**
 * Match Team Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamCollectionFilter extends NestedCollectionInputFilter
{
    public function __construct(InputFilterInterface $ifMatchTeam)
    {
        $this->setInputFilter($ifMatchTeam);
    }

    public function isValid()
    {
        $result = parent::isValid();

        // Check is futile if we aren't validating the whole collection
        if ( !is_null($this->validationGroup) && ( !isset($this->validationGroup['H']) || !in_array('team', $this->validationGroup['H']) || !isset($this->validationGroup['A']) || !in_array('team', $this->validationGroup['A']) ) ) {
            return $result;
        }

        // Ensure we have a complete set of teams
        $values = $this->getValues();
        if ( !isset($values['H']) || !isset($values['A']) ) {
            return false;
        }

        // @TODO better way to ensure collection has unique teams?
        $teams = [];
        $players = [];
        foreach ($values as $key=>$membership) {
            // Team must be unique per match
            if ( in_array($membership['team'], $teams) ) {
                $this->collectionMessages[$key] = ['team' => ['A team cannot play against itself!']];
                $result = false;
            }
            array_push($teams, $membership['team']);

            if ( !isset($membership['players']) || empty($membership['players']) ) {
                continue;
            }

            // Player must be unique per match
            foreach ($membership['players'] as $pkey=>$player) {
                if ( empty($player['player']) ) {
                    continue;
                }
                if ( in_array($player['player'], $players) ) {
                    $this->collectionMessages[$key]['players'][$pkey] = ['player' => ['This player has already been added!']];
                    $result = false;
                }
                array_push($players, $player['player']);
            }
        }

        return $result;
    }
}
