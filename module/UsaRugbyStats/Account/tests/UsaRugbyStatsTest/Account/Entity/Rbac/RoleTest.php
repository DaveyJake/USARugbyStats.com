<?php
namespace UsaRugbyStatsTest\Entity\Rbac;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Account\Entity\Rbac\Role;
use UsaRugbyStats\Account\Entity\Rbac\Permission;

class RoleTest extends ServiceManagerTestCase
{
    public function testConstructorSetsRoleName()
    {
        $obj = new Role('foo');
        $this->assertEquals('foo', $obj->getName());
    }

    public function testConstructorSetRoleNameNullWhenNotSpecified()
    {
        $obj = new Role();
        $this->assertNull($obj->getName());
    }

    public function testToString()
    {
        $obj = new Role('foo');
        $this->assertEquals('foo', (string) $obj);
    }

    public function testIdentifier()
    {
        $obj = new Role();
        $obj->setId(999);
        $this->assertEquals(999, $obj->getId());
    }

    public function testAddChildren()
    {
        $c0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c0->shouldReceive('getRole')->once()->andReturn(new Role('child0'));

        $obj = new Role();
        $collection = $obj->getChildren();
        $collection->add($c0); // child0 is pre-existing

        $c1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c1->shouldReceive('getRole')->once()->andReturn(new Role('child1'));
        $c2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c2->shouldReceive('getRole')->once()->andReturn(new Role('child2'));

        $newCollection = new ArrayCollection();
        $newCollection->add($c0);
        $newCollection->add($c1);

        // Do the add
        $obj->addChildren($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getChildren());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getChildren());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getChildren());
        $this->assertEquals(3, $obj->getChildren()->count());
    }

    public function testAddChild()
    {
        $obj = new Role();

        $c1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c1->shouldReceive('getRole')->once()->andReturn(new Role('child1'));
        $c2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c2->shouldReceive('getRole')->once()->andReturn(new Role('child2'));

        // Add one to the existing collection
        $collection = $obj->getChildren();
        $collection->add($c1);

        // Do teh add
        $obj->addChild($c2);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getChildren());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getChildren());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getChildren());
        $this->assertEquals(2, $obj->getChildren()->count());
    }

    public function testRemoveChildren()
    {
        $c0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c0->shouldReceive('getRole')->once()->andReturn(new Role('child0'));
        $c1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c1->shouldReceive('getRole')->once()->andReturn(new Role('child1'));
        $c2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c2->shouldReceive('getRole')->once()->andReturn(new Role('child2'));

        $obj = new Role();
        $collection = $obj->getChildren();
        $collection->add($c0);
        $collection->add($c1);
        $collection->add($c2);

        $removeCollection = new ArrayCollection();
        $removeCollection->add($c0);
        $removeCollection->add($c1);

        // Do the remove
        $obj->removeChildren($removeCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getChildren());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getChildren());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getChildren());
        $this->assertEquals(1, $obj->getChildren()->count());
        $this->assertFalse($obj->getChildren()->contains($c0));
        $this->assertFalse($obj->getChildren()->contains($c1));
        $this->assertTrue($obj->getChildren()->contains($c2));
    }

    public function testRemoveChild()
    {
        $obj = new Role();

        $c1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c1->shouldReceive('getRole')->once()->andReturn(new Role('child1'));
        $c2 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c2->shouldReceive('getRole')->once()->andReturn(new Role('child2'));

        // Add one to the existing collection
        $collection = $obj->getChildren();
        $collection->add($c1);
        $collection->add($c2);

        // Do teh add
        $obj->removeChild($c2);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getChildren());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Role', $obj->getChildren());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getChildren());
        $this->assertEquals(1, $obj->getChildren()->count());
        $this->assertTrue($obj->getChildren()->contains($c1));
        $this->assertFalse($obj->getChildren()->contains($c2));
    }

    public function testHasChildren()
    {
        $obj = new Role();

        $this->assertFalse($obj->hasChildren());

        $c1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        $c1->shouldReceive('getRole')->once()->andReturn(new Role('child1'));
        $obj->addChild($c1);

        $this->assertTrue($obj->hasChildren());
    }

    public function testAddPermissions()
    {
        $p0 = new Permission('perm0');

        $obj = new Role();
        $collection = $obj->getPermissions();
        $collection->add($p0); // perm0 is pre-existing

        $p1 = new Permission('perm1');
        $p2 = new Permission('perm2');

        $newCollection = new ArrayCollection();
        $newCollection->add($p0);
        $newCollection->add($p1);

        // Do the add
        $obj->addPermissions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPermissions());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Permission', $obj->getPermissions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPermissions());
        $this->assertEquals(3, $obj->getPermissions()->count());
    }

    public function testAddPermission()
    {
        $obj = new Role();

        $p1 = new Permission('perm1');
        $p2 = new Permission('perm2');

        // Add one to the existing collection
        $collection = $obj->getPermissions();
        $collection->add($p1);

        // Do teh add
        $obj->addPermission($p2);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPermissions());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Permission', $obj->getPermissions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPermissions());
        $this->assertEquals(2, $obj->getPermissions()->count());
    }

    public function testRemovePermissions()
    {
        $p0 = new Permission('perm0');
        $p1 = new Permission('perm1');
        $p2 = new Permission('perm2');

        $obj = new Role();
        $collection = $obj->getPermissions();
        $collection->add($p0);
        $collection->add($p1);
        $collection->add($p2);

        $removeCollection = new ArrayCollection();
        $removeCollection->add($p0);
        $removeCollection->add($p1);

        // Do the remove
        $obj->removePermissions($removeCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPermissions());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Permission', $obj->getPermissions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPermissions());
        $this->assertEquals(1, $obj->getPermissions()->count());
        $this->assertFalse($obj->getPermissions()->contains($p0));
        $this->assertFalse($obj->getPermissions()->contains($p1));
        $this->assertTrue($obj->getPermissions()->contains($p2));
    }

    public function testRemovePermission()
    {
        $obj = new Role();

        $p1 = new Permission('perm1');
        $p2 = new Permission('perm2');

        // Add one to the existing collection
        $collection = $obj->getPermissions();
        $collection->add($p1);
        $collection->add($p2);

        // Do teh add
        $obj->removePermission($p2);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPermissions());
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\Account\Entity\Rbac\Permission', $obj->getPermissions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPermissions());
        $this->assertEquals(1, $obj->getPermissions()->count());
        $this->assertTrue($obj->getPermissions()->contains($p1));
        $this->assertFalse($obj->getPermissions()->contains($p2));
    }

    public function testHasPermission()
    {
        $obj = new Role();

        $this->assertFalse($obj->hasPermission('foobar'));

        $p1 = new Permission('foobar');
        $obj->addPermission($p1);

        $this->assertTrue($obj->hasPermission('foobar'));
    }
}
