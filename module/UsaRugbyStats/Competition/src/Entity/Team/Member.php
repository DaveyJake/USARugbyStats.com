<?php
namespace UsaRugbyStats\Competition\Entity\Team;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member as MemberRole;
use UsaRugbyStats\Competition\Entity\Team;
/**
 * Team Member
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Member
{
    /**
     * Record Primary Key
     *
     * @var integer
     */
    protected $id;

    /**
     * Link to Account Role storing the membership
     *
     * @var MemberRole
     */
    protected $role;

    /**
     * Team this membership is associated with
     *
     * @var Team
     */
    protected $team;

    /**
     * Membership Status Flag
     * NULL = unknown, 0 = unpaid, 1 = pending, 2 = current, 3 = grace period, 4 = lapsed
     *
     * @var integer
     */
    protected $membershipStatus;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return MemberRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param MemberRole $role
     */
    public function setRole(MemberRole $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMembershipStatus()
    {
        return $this->membershipStatus;
    }

    /**
     * @param int|null $membershipStatus
     */
    public function setMembershipStatus($membershipStatus)
    {
        if ( ! is_null($membershipStatus) && ! in_array((int) $membershipStatus, [0,1,2,3,4], true) ) {
            $membershipStatus = null;
        }
        $this->membershipStatus = $membershipStatus;

        return $this;
    }

}
