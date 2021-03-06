<?php
namespace UsaRugbyStats\Competition\Entity\Team;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member as MemberRole;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Application\Entity\AccountInterface;
/**
 * Team Member
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Member
{
    const STATUS_UNKNOWN = null;
    const STATUS_UNPAID = 0;
    const STATUS_PENDING = 1;
    const STATUS_CURRENT = 2;
    const STATUS_GRACEPERIOD = 3;
    const STATUS_LAPSED = 4;

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
     * Sort Key to maintain association order
     *
     * @var string
     */
    protected $sortKey;

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
        $this->updateSortKey();

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
        $this->updateSortKey();

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

    /**
     * @return string
     */
    public function getSortKey()
    {
        return $this->sortKey;
    }

    /**
     * @param  string $key
     * @return self
     */
    public function setSortKey($key)
    {
        $this->sortKey = $key;

        return $this;
    }

    public function updateSortKey()
    {
        $sortKey = '';
        if ($this->team instanceof Team) {
            $sortKey .= $this->team->getName();
        }
        if ( $this->role instanceof MemberRole && $this->role->getAccount() instanceof AccountInterface ) {
            $sortKey .= $this->role->getAccount()->getDisplayName();
        }
        $this->setSortKey(preg_replace('{[^a-z0-9_-]}is', '', $sortKey) ?: null);
    }

}
