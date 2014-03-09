<?php
namespace UsaRugbyStats\Account\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use ZfcRbac\Identity\IdentityInterface;
use UsaRugbyStats\Application\Entity\Account as BaseAccount;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;
use Doctrine\Common\Collections\Collection;

class Account extends BaseAccount implements UserInterface, IdentityInterface
{
    /**
     * @var Collection
     */
    protected $roleAssignments;
    
    /**
     * @var array
     */
    protected $roleCache;
    
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
     * @param RoleAssignment $role
     * @return self
     */
    public function addRoleAssignment(RoleAssignment $ra)
    {
        $this->roleCache = array();
        $this->roleAssignments->add($ra);
        return $this;
    }
    
    /**
     * @param RoleAssignment $role
     * @return bool
     */
    public function hasRoleAssignment(RoleAssignment $ra)
    {
        return $this->roleAssignments->contains($ra);
    }

    /**
     * @param string|Role $role
     * @return bool
     */
    public function hasRole($role)
    { 
        return in_array((string)$role, $this->getRoles());
    }    

    /**
     * @return array<Role>
     */
    public function getRoles()
    {
        if (empty($this->roleCache)) {
            $this->roleCache = array();
            foreach ( $this->roleAssignments as $ra ) {
                $this->roleCache[] = $ra->getRole();
            }
        }
        return $this->roleCache;
    }
}