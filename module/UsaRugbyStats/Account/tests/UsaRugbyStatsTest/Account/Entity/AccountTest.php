<?php
namespace UsaRugbyStatsTest\Account\Controller\Plugin;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Account;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Account\Entity\Rbac\Role;


class AccountTest extends ServiceManagerTestCase
{
    public function testSetRoleAssignments()
    {
        $obj = new Account();
        $collection = $obj->getRoleAssignments();
        
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->once()->andReturn(new Role('member'));
        
        $newCollection = new ArrayCollection();
        $newCollection->add($ra0);
        $newCollection->add($ra1);
        
        // Do the add
        $obj->setRoleAssignments($newCollection);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getRoleAssignments());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getRoleAssignments());
        $this->assertEquals(2, $obj->getRoleAssignments()->count());
    }
    
    public function testAddRoleAssignment()
    {
        $obj = new Account();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->once()->andReturn(new Role('member'));
    
        // Add one to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
        
        // Do teh add
        $obj->addRoleAssignment($ra1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getRoleAssignments());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getRoleAssignments());
        $this->assertEquals(2, $obj->getRoleAssignments()->count());
    }

    public function testGetRoleAssignment()
    {
        $obj = new Account();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->once()->andReturn(new Role('member'));
    
        // Add roles to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
        $collection->add($ra1);

        $this->assertSame($ra0, $obj->getRoleAssignment('super_admin'));
        $this->assertSame($ra1, $obj->getRoleAssignment('member'));
    }
    
    public function testHasRoleAssignment()
    {
        $obj = new Account();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->once()->andReturn(new Role('member'));
    
        // Add roles to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
    
        $this->assertTrue($obj->hasRoleAssignment($ra0));
        $this->assertFalse($obj->hasRoleAssignment($ra1));
    }
    
    public function testGetRoleAssignmentOnNonexistentRoleNameReturnsNull()
    {
        $obj = new Account();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('baz')->once()->andReturn(null);
    
        $this->assertNull($obj->getRoleAssignment('baz'));
    }

    public function testGetRoleAssignmentReturnsFirstMatchWhenMultipleAssignmentsExistForSameRole()
    {
        $obj = new Account();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
    
        // Add roles to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
        $collection->add($ra1);
    
        $this->assertSame($ra0, $obj->getRoleAssignment('super_admin'));
        $this->assertNotSame($ra1, $obj->getRoleAssignment('super_admin'));
    }
    
    public function testGetRoles()
    {
        $obj = new Account();
        $collection = $obj->getRoleAssignments();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $collection->add($ra0);

        $this->assertCount(1, $obj->getRoles());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getRoles());
        $this->assertEquals(['super_admin'], $obj->getRoles());
    }
    
    public function testHasRole()
    {
        $obj = new Account();
        $collection = $obj->getRoleAssignments();
    
        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->once()->andReturn(new Role('super_admin'));
        $collection->add($ra0);
    
        $this->assertTrue($obj->hasRole('super_admin'));
        $this->assertFalse($obj->hasRole('foobar'));
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getRoles());
        $this->assertEquals(['super_admin'], $obj->getRoles());
    }

}