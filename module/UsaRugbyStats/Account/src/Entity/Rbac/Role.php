<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use Rbac\Role\HierarchicalRoleInterface;
use Rbac\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Role implements HierarchicalRoleInterface
{
    protected $id;
    protected $name;
    
    /**
     * Child Roles 
     * @var ArrayCollection
     */
    protected $children;
    
    /**
     * Permissions associated with this role
     * @var ArrayCollection
     */
    protected $permissions;

    public function __construct($name = null)
    {
        if ( !empty($name) ) {
            $this->setName($name);
        }        
        $this->children = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

	public function getId()
    {
        return $this->id;
    }

	public function setId($id)
    {
        $this->id = $id;
    }

	public function getName()
    {
        return $this->name;
    }

	public function setName($name)
    {
        $this->name = $name;
    }

	public function getChildren()
    {
        return $this->children;
    }

	public function addChildren(Collection $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
        return $this;
    }
    
    public function addChild(RoleInterface $child)
    {
        $this->children->add($child);
        return $this;
    }

    public function removeChildren(Collection $children)
    {
        foreach ($children as $child) {
            $this->removeChild($child);
        }
        return $this;
    }
    
    public function removeChild(RoleInterface $child)
    {
        $this->children->removeElement($child);
        return $this;
    }
    
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
    
    public function addPermissions(Collection $perms)
    {
        foreach ($perms as $p) {
            $this->addPermission($p);
        }
        return $this;
    }
    
    public function addPermission(Permission $perm)
    {
        $this->permissions->add($perm);
        return $this;
    }
    
    public function removePermissions(Collection $perms)
    {
        foreach ($perms as $p) {
            $this->removePermission($p);
        }
        return $this;
    }
    
    public function removePermission(Permission $perm)
    {
        $this->permissions->removeElement($perm);
        return $this;
    }
    
	public function hasPermission($permission)
    {
        return $this->permissions->contains($permission);
    }

}