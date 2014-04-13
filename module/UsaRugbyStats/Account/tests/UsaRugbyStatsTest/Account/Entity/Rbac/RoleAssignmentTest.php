<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;

class RoleAssignmentTest extends ServiceManagerTestCase
{
    public function testIdentifier()
    {
        $obj = new RoleAssignmentTestEntity();
        $this->assertNull($obj->getId());
        
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
        
        $obj->setId(NULL);
        $this->assertNull($obj->getId());
    }
    
    public function testAccount()
    {
        $acct = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');
        
        $obj = new RoleAssignmentTestEntity();
        $this->assertNull($obj->getAccount());

        $obj->setAccount($acct);
        $this->assertSame($acct, $obj->getAccount());
    }

    public function testAccountCanBeSetToNull()
    {
        $obj = new RoleAssignmentTestEntity();
        $obj->setAccount(NULL);
        $this->assertNull($obj->getAccount());
    }

    public function testRole()
    {
        $role = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
    
        $obj = new RoleAssignmentTestEntity();
        $this->assertNull($obj->getRole());
    
        $obj->setRole($role);
        $this->assertSame($role, $obj->getRole());
    }
    
    public function testRoleCanBeSetToNull()
    {
        $obj = new RoleAssignmentTestEntity();
        $obj->setRole(NULL);
        $this->assertNull($obj->getRole());
    }
}