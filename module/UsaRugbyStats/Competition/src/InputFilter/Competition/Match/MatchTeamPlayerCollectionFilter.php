<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Match Team Player Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamPlayerCollectionFilter extends CollectionInputFilter
{
    public function __construct(InputFilterInterface $ifMatchTeamPlayer)
    {
        $this->setInputFilter($ifMatchTeamPlayer);
        $this->setIsRequired(false);
    }

    public function isValid()
    {
        $result = parent::isValid();

        $values = $this->getValues();
        $numbers   = [];
        $positions = [];
        foreach ($values as $key=>$membership) {
            // Ensure player number is unique per side
            if ( in_array($membership['number'], $numbers, true) ) {
                $this->collectionMessages[$key]['number'] = ['Duplicate!'];
                $result = false;
            }
            array_push($numbers, $membership['number']);

            // Ensure player position is unique per side
            if ( in_array($membership['position'], $positions, true) ) {
                $this->collectionMessages[$key]['position'] = ['Duplicate!'];
                $result = false;
            }
            array_push($positions, $membership['position']);
        }

        return $result;
    }
}
