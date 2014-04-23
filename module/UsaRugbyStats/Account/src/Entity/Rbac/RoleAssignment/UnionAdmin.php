<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use UsaRugbyStats\Competition\Entity\Union;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class UnionAdmin extends BaseAssignment
{
    protected $managedUnions;

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
        return $this->managedUnions;
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

    public function getDiscriminator()
    {
        return 'union_admin';
    }
}
