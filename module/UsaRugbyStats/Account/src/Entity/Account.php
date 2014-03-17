<?php
namespace UsaRugbyStats\Account\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Application\Entity\Account as BaseAccount;
use UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;
use Doctrine\Common\Collections\Collection;
use ZfcUser\Entity\UserInterface;
use ZfcRbac\Identity\IdentityInterface;


class Account extends BaseAccount implements UserInterface, AccountRbacInterface, IdentityInterface
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
    
    public function getRoleAssignment($rolename)
    {
        foreach ( $this->roleAssignments as $obj ) {
            if ( $rolename == $obj->getRole()->getName() ) {
                return $obj;
            }
        }
        return null;
    }
    
    /**
     * @param Collection $roleAssignments
     * @return self
     */
    public function setRoleAssignments(Collection $roleAssignments)
    {
        $this->roleAssignments->clear();
        $this->addRoleAssignments($roleAssignments);
        return $this;
    }

    /**
     * @param Collection $roleAssignments
     * @return self
     */
    public function addRoleAssignments(Collection $roleAssignments)
    {
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
     * @param Collection $roleAssignments
     * @return self
     */
    public function removeRoleAssignments(Collection $roleAssignments)
    {
        if(count($roleAssignments)){
            foreach($roleAssignments as $ra){
                $this->removeRoleAssignment($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param RoleAssignment $role
     * @return self
     */
    public function removeRoleAssignment(RoleAssignment $ra)
    {
        $this->roleCache = array();
        $ra->setAccount(NULL);
        $this->roleAssignments->removeElement($ra);
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