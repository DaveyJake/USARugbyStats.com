<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use UsaRugbyStats\Competition\Entity\Team;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class TeamAdmin extends BaseAssignment
{
    protected $managedTeams;
    protected $managedTeamsSorted;

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->managedTeams = new ArrayCollection();
    }

    public function __clone()
    {
        $this->managedTeams = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getManagedTeams()
    {
        if ( ! $this->managedTeamsSorted && ! empty($this->managedTeams) ) {
            $this->sortManagedTeams();
        }

        return $this->managedTeams;
    }

    protected function sortManagedTeams()
    {
        $collValues = [];
        $collSortKeys = [];
        foreach ($this->managedTeams as $item) {
            $this->managedTeams->removeElement($item);
            if (! $item instanceof Team) {
                continue;
            }
            array_push($collValues, $item);
            array_push($collSortKeys, $item->getName());
        }
        array_multisort($collSortKeys, SORT_ASC, $collValues);
        foreach ($collValues as $item) {
            $this->managedTeams->add($item);
        }
        $this->managedTeamsSorted = true;
    }

    /**
     * @param  Collection $managedTeams
     * @return self
     */
    public function setManagedTeams(Collection $managedTeams)
    {
        $this->managedTeams->clear();
        $this->addManagedTeams($managedTeams);

        return $this;
    }

    /**
     * @param  Collection $managedTeams
     * @return self
     */
    public function addManagedTeams(Collection $managedTeams)
    {
        if (count($managedTeams)) {
            foreach ($managedTeams as $t) {
                $this->addManagedTeam($t);
            }
        }

        return $this;
    }

    /**
     * @param  Team $t
     * @return self
     */
    public function addManagedTeam(Team $t)
    {
        // Only add Team if it's not already here
        if ( ! $this->hasManagedTeam($t) ) {
            $this->managedTeams->add($t);
            $this->managedTeamsSorted = false;
        }

        return $this;
    }

    /**
     * @param  Team $t
     * @return bool
     */
    public function hasManagedTeam(Team $t)
    {
        return $this->managedTeams->contains($t);
    }

    /**
     * @param  Collection $managedTeams
     * @return self
     */
    public function removeManagedTeams(Collection $managedTeams)
    {
        if (count($managedTeams)) {
            foreach ($managedTeams as $t) {
                $this->removeManagedTeam($t);
            }
        }

        return $this;
    }

    /**
     * @param  Team $t
     * @return self
     */
    public function removeManagedTeam(Team $t)
    {
        $this->managedTeams->removeElement($t);

        return $this;
    }

    public function getDiscriminator()
    {
        return 'team_admin';
    }
}
