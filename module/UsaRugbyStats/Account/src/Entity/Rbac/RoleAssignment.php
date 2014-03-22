<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use UsaRugbyStats\Application\Entity\AccountInterface;

abstract class RoleAssignment 
{
    protected $id;
    
    protected $account;
    
    protected $role;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount(AccountInterface $account = NULL)
    {
        $this->account = $account;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(Role $role = NULL)
    {
        $this->role = $role;
    }
    
    abstract function getDiscriminator();
    
}