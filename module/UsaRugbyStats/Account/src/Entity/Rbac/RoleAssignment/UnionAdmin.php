<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use UsaRugbyStats\Competition\Entity\Union;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class UnionAdmin extends BaseAssignment
{
    protected $managedUnions;
    protected $managedUnionsSorted = false;

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->managedUnions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->managedUnions = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getManagedUnions()
    {
        if ( ! $this->managedUnionsSorted && !empty($this->managedUnions) ) {
            $this->sortManagedUnions();
        }

        return $this->managedUnions;
    }

    protected function sortManagedUnions()
    {
        $collValues = [];
        $collSortKeys = [];
        foreach ($this->managedUnions as $item) {
            $this->managedUnions->removeElement($item);
            if (! $item instanceof Union) {
                continue;
            }
            array_push($collValues, $item);
            array_push($collSortKeys, $item->getName());
        }
        array_multisort($collSortKeys, SORT_ASC, $collValues);
        foreach ($collValues as $item) {
            $this->managedUnions->add($item);
        }
        $this->managedUnionsSorted = true;
    }

    /**
     * @param  Collection $managedUnions
     * @return self
     */
    public function setManagedUnions(Collection $managedUnions)
    {
        $this->managedUnions->clear();
        $this->addManagedUnions($managedUnions);

        return $this;
    }

    /**
     * @param  Collection $managedUnions
     * @return self
     */
    public function addManagedUnions(Collection $managedUnions)
    {
        if (count($managedUnions)) {
            foreach ($managedUnions as $t) {
                $this->addManagedUnion($t);
            }
        }

        return $this;
    }

    /**
     * @param  Union $t
     * @return self
     */
    public function addManagedUnion(Union $t)
    {
        // Only add Union if it's not already here
        if ( ! $this->hasManagedUnion($t) ) {
            $this->managedUnions->add($t);
            $this->managedUnionsSorted = false;
        }

        return $this;
    }

    /**
     * @param  Union $t
     * @return bool
     */
    public function hasManagedUnion(Union $t)
    {
        return $this->managedUnions->contains($t);
    }

    /**
     * @param  Collection $managedUnions
     * @return self
     */
    public function removeManagedUnions(Collection $managedUnions)
    {
        if (count($managedUnions)) {
            foreach ($managedUnions as $t) {
                $this->removeManagedUnion($t);
            }
        }

        return $this;
    }

    /**
     * @param  Union $t
     * @return self
     */
    public function removeManagedUnion(Union $t)
    {
        $this->managedUnions->removeElement($t);

        return $this;
    }

    public function getManagedTeams()
    {
        $set = array();
        foreach ($this->getManagedUnions() as $union) {
            if (! $union instanceof Union) {
                continue;
            }
            $set = array_merge($set, $union->getTeams()->toArray());
        }

        return new ArrayCollection($set);
    }

    public function hasManagedTeam(Team $t)
    {
        foreach ($this->managedUnions as $union) {
            if (! $union instanceof Union) {
                continue;
            }
            if ( $union->hasTeam($t) ) {
                return true;
            }
        }

        return false;
    }

    public function getDiscriminator()
    {
        return 'union_admin';
    }
}
