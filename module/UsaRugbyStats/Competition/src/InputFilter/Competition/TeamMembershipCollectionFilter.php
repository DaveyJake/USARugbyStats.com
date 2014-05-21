<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilterInterface;
use UsaRugbyStats\Application\Common\NestedCollectionInputFilter;

/**
 * Team Membership Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamMembershipCollectionFilter extends NestedCollectionInputFilter
{
    public function __construct(InputFilterInterface $ifTeamMembership)
    {
        $this->setInputFilter($ifTeamMembership);
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
                $this->collectionMessages[$key] = ['team' => ['This team has already been added!']];
                $result = false;
            }
            array_push($teams, $membership['team']);
        }

        return $result;
    }
}
