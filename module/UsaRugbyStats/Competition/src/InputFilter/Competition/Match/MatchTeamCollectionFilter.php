<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Match Team Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamCollectionFilter extends CollectionInputFilter
{
    public function __construct(InputFilterInterface $ifMatchTeam)
    {
        $this->setInputFilter($ifMatchTeam);
        $this->setIsRequired(false);
    }

    public function isValid()
    {
        $result = parent::isValid();

        // @TODO better way to ensure collection has unique teams?
        $values = $this->getValues();
        $teams = [];
        foreach ($values as $key=>$membership) {
            if ( in_array($membership['team'], $teams) ) {
                $this->collectionMessages[$key] = ['team' => ['A team cannot play against itself!']];
                $result = false;
            }
            array_push($teams, $membership['team']);
        }

        return $result;
    }
}
