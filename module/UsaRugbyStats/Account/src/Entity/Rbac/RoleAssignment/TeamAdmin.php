<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;
use UsaRugbyStats\Application\Entity\Team;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class TeamAdmin extends BaseAssignment
{
    protected $managedTeams;   

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->managedTeams = new ArrayCollection();
    }
    
    /**
     * @return Collection
     */
    public function getManagedTeams()
    {
        return $this->managedTeams;
    }
    
    /**
     * @param Collection $managedTeams
     * @return self
     */
    public function setManagedTeams(Collection $managedTeams)
    {
        $this->managedTeams->clear();
        if(count($managedTeams)){
            foreach($managedTeams as $t) {
                $this->addManagedTeam($t);
            }
        }
        return $this;
    }
    
    /**
     * @param ManagedTeam $t
     * @return self
     */
    public function addManagedTeam(Team $t)
    {
        $this->managedTeams->add($t);
        return $this;
    }
    
    /**
     * @param ManagedTeam $t
     * @return bool
     */
    public function hasManagedTeam(Team $t)
    {
        return $this->managedTeams->contains($t);
    }

    
}