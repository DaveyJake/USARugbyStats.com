<?php
namespace UsaRugbyStatsTest\Account\Entity;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Account;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class AccountTest extends ServiceManagerTestCase
{

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new Account();
        $coll = $obj->getRoleAssignments();

        $newObj = clone $obj;
        $this->assertNotSame($coll, $newObj->getRoleAssignments());
    }

    public function testSetRoleAssignments()
    {
        $obj = new Account();
        $collection = $obj->getRoleAssignments();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('setAccount')->once();
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('setAccount')->once();

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
        $ra0->shouldReceive('setAccount')->never();
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('setAccount')->once();

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

    public function testAddRoleAssignments()
    {
        $obj = new Account();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('setAccount')->never();
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('setAccount')->once();
        $ra2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra2->shouldReceive('setAccount')->once();

        // Add one to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($ra1);
        $coll->add($ra2);
        $obj->addRoleAssignments($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getRoleAssignments());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getRoleAssignments());
        $this->assertEquals(3, $obj->getRoleAssignments()->count());
    }

    public function testGetRoleAssignment()
    {
        $obj = new Account();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->andReturn(new Role('member'));

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
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');

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

        $this->assertNull($obj->getRoleAssignment('baz'));
    }

    public function testGetRoleAssignmentReturnsFirstMatchWhenMultipleAssignmentsExistForSameRole()
    {
        $obj = new Account();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('getRole')->andReturn(new Role('super_admin'));
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('getRole')->andReturn(new Role('super_admin'));

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

    public function testRemoveRoleAssignment()
    {
        $obj = new Account();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('setAccount')->once();
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('setAccount')->never();

        // Add two to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
        $collection->add($ra1);

        // Do the remove
        $obj->removeRoleAssignment($ra0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getRoleAssignments());
        $this->assertSame($collection, $obj->getRoleAssignments());
        $this->assertEquals(1, $obj->getRoleAssignments()->count());
        $this->assertFalse($obj->getRoleAssignments()->contains($ra0));
        $this->assertTrue($obj->getRoleAssignments()->contains($ra1));
    }

    public function testRemoveRoleAssignments()
    {
        $obj = new Account();

        $ra0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra0->shouldReceive('setAccount')->once();
        $ra1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra1->shouldReceive('setAccount')->never();
        $ra2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $ra2->shouldReceive('setAccount')->once();

        // Add one to the existing collection
        $collection = $obj->getRoleAssignments();
        $collection->add($ra0);
        $collection->add($ra1);
        $collection->add($ra2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($ra0);
        $coll->add($ra2);
        $obj->removeRoleAssignments($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getRoleAssignments());
        $this->assertSame($collection, $obj->getRoleAssignments());
        $this->assertEquals(1, $obj->getRoleAssignments()->count());
        $this->assertFalse($obj->getRoleAssignments()->contains($ra0));
        $this->assertTrue($obj->getRoleAssignments()->contains($ra1));
        $this->assertFalse($obj->getRoleAssignments()->contains($ra2));
    }

}
