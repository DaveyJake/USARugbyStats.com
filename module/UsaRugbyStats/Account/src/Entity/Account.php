<?php
namespace UsaRugbyStats\Account\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use ZfcRbac\Identity\IdentityInterface;
use UsaRugbyStats\Application\Entity\Account as BaseAccount;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Account\Entity\Rbac\AccountRole;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class Account extends BaseAccount implements UserInterface, IdentityInterface
{
    /**
     * @var Collection
     */
    protected $roleAssignments;
    
    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->roleAssignments = new ArrayCollection();
    }
    
    /**
     * @return Collection
     */
    public function getRoleAssignments()
    {
        return $this->roleAssignments;
    }
    
    /**
     * @param Collection $roleAssignments
     * @return self
     */
    public function setRoleAssignments(Collection $roleAssignments)
    {
        $this->roleAssignments = new ArrayCollection();
        if(count($roleAssignments)){
            foreach($roleAssignments as $ra){
                $this->addRoleAssignment($ra);
            }
        }    
        return $this;
    }
    
    /**
     * @param AccountRole $role
     * @return self
     */
    public function addRoleAssignment(AccountRole $ra)
    {
        $this->roleAssignments->set((string)$ra->getRole(), $ra);
        return $this;
    }
    
    /**
     * @param AccountRole $role
     * @return bool
     */
    public function hasRoleAssignment(AccountRole $ra)
    {
        return $this->roleAssignments->contains($ra);
    }

    /**
     * @param string|Role $role
     * @return bool
     */
    public function hasRole($role)
    { 
        return isset($this->roleAssignments[(string)$role]);
    }    

    /**
     * @param AccountRole $role
     * @return bool
     */
    public function getRoles()
    {
        $roles = array();
        foreach ( $this->roleAssignments as $ra ) {
            $roles[] = $ra->getRole();
        }
        return $roles;
    }
}