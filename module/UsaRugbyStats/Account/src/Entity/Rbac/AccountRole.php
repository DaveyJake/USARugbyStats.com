<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use UsaRugbyStats\Application\Entity\AccountInterface;
class AccountRole 
{

    protected $id;
    
    protected $account;
    
    protected $role;
    
    protected $restrictions;
    
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

    public function setAccount(AccountInterface $account)
    {
        $this->account = $account;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    public function getRestrictions()
    {
        return $this->restrictions;
    }

    public function setRestrictions(array $restrictions)
    {
        $this->restrictions = $restrictions;
    }
    
    
}