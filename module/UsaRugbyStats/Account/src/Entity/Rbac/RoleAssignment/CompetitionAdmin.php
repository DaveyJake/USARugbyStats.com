<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionAdmin extends BaseAssignment
{
    protected $managedCompetitions;
    protected $managedCompetitionsSorted = false;

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->managedCompetitions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->managedCompetitions = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getManagedCompetitions()
    {
        if ( ! $this->managedCompetitionsSorted && ! empty($this->managedCompetitions) ) {
            $this->sortManagedCompetitions();
        }

        return $this->managedCompetitions;
    }

    protected function sortManagedCompetitions()
    {
        $collValues = [];
        $collSortKeys = [];
        foreach ($this->managedCompetitions as $item) {
            $this->managedCompetitions->removeElement($item);
            if (! $item instanceof Competition) {
                continue;
            }
            array_push($collValues, $item);
            array_push($collSortKeys, $item->getName());
        }
        array_multisort($collSortKeys, SORT_ASC, $collValues);
        foreach ($collValues as $item) {
            $this->managedCompetitions->add($item);
        }
        $this->managedCompetitionsSorted = true;
    }

    /**
     * @param  Collection $managedCompetitions
     * @return self
     */
    public function setManagedCompetitions(Collection $managedCompetitions)
    {
        $this->managedCompetitions->clear();
        $this->addManagedCompetitions($managedCompetitions);

        return $this;
    }

    /**
     * @param  Collection $managedCompetitions
     * @return self
     */
    public function addManagedCompetitions(Collection $managedCompetitions)
    {
        if (count($managedCompetitions)) {
            foreach ($managedCompetitions as $t) {
                $this->addManagedCompetition($t);
            }
        }

        return $this;
    }

    /**
     * @param  Competition $t
     * @return self
     */
    public function addManagedCompetition(Competition $t)
    {
        // Only add Competition if it's not already here
        if ( ! $this->hasManagedCompetition($t) ) {
            $this->managedCompetitions->add($t);
            $this->managedCompetitionsSorted = false;
        }

        return $this;
    }

    /**
     * @param  Competition $t
     * @return bool
     */
    public function hasManagedCompetition(Competition $t)
    {
        return $this->managedCompetitions->contains($t);
    }

    /**
     * @param  Collection $managedCompetitions
     * @return self
     */
    public function removeManagedCompetitions(Collection $managedCompetitions)
    {
        if (count($managedCompetitions)) {
            foreach ($managedCompetitions as $t) {
                $this->removeManagedCompetition($t);
            }
        }

        return $this;
    }

    /**
     * @param  Competition $t
     * @return self
     */
    public function removeManagedCompetition(Competition $t)
    {
        $this->managedCompetitions->removeElement($t);

        return $this;
    }

    public function getDiscriminator()
    {
        return 'competition_admin';
    }
}
