<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilterInterface;
use UsaRugbyStats\Application\Common\NestedCollectionInputFilter;

/**
 * Competition Division Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class DivisionCollectionFilter extends NestedCollectionInputFilter
{
    public function __construct(InputFilterInterface $ifDivision)
    {
        $this->setInputFilter($ifDivision);
        $this->setIsRequired(false);
    }

    public function isValid()
    {
        $result = parent::isValid();

        // @TODO better way to ensure collection has unique teams?
        $values = $this->getValues();
        $teams = [];
        foreach ($values as $divkey=>$divdata) {
            foreach ($divdata['teamMemberships'] as $tmkey=>$membership) {
                if ( in_array($membership['team'], $teams) ) {
                    $this->collectionMessages[$divkey]['teamMemberships'][$tmkey] = ['team' => ['This team has already been added!']];
                    $result = false;
                }
                array_push($teams, $membership['team']);
            }
        }

        return $result;
    }
}
