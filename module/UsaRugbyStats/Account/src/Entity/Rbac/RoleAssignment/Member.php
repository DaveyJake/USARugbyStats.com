<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Team\Member as TeamMember;
use UsaRugbyStats\Competition\Entity\Team;

class Member extends BaseAssignment
{
    protected $memberships;

    public function __construct()
    {
        $this->memberships = new ArrayCollection();
    }

    public function __clone()
    {
        $this->memberships = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    public function getMembershipForTeam(Team $t)
    {
        $result = $this->memberships->filter(function (TeamMember $obj) use ($t) {
            if ( is_null($obj->getTeam()) ) {
                return false;
            }

            return $obj->getTeam()->getId() == $t->getId();
        });

        return $result->count()
            ? $result->first()
            : null;
    }

    /**
     * @param  Collection $memberships
     * @return self
     */
    public function setMemberships(Collection $memberships)
    {
        $this->memberships->clear();
        $this->addMemberships($memberships);

        return $this;
    }

    /**
     * @param  Collection $memberships
     * @return self
     */
    public function addMemberships(Collection $memberships)
    {
        if (count($memberships)) {
            foreach ($memberships as $t) {
                $this->addMembership($t);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMember $t
     * @return self
     */
    public function addMembership(TeamMember $t)
    {
        // Only add Team if it's not already here
        if ( ! $this->hasMembership($t) ) {
            $t->setRole($this);
            $this->memberships->add($t);
        }

        return $this;
    }

    /**
     * @param  TeamMember|Team $t
     * @return bool
     */
    public function hasMembership($t)
    {
        if ($t instanceof TeamMember) {
            return $this->memberships->contains($t);
        }
        if ($t instanceof Team) {
            return $this->getMembershipForTeam($t) != NULL;
        }

        return false;
    }

    /**
     * @param  Collection $memberships
     * @return self
     */
    public function removeMemberships(Collection $memberships)
    {
        if (count($memberships)) {
            foreach ($memberships as $t) {
                $this->removeMembership($t);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMember $t
     * @return self
     */
    public function removeMembership(TeamMember $t)
    {
        $t->setRole(null);
        $this->memberships->removeElement($t);

        return $this;
    }


    public function getDiscriminator()
    {
        return 'member';
    }
}
