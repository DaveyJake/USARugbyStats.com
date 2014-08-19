<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;
use Doctrine\Common\Collections\ArrayCollection;

class UnionAdminTest extends ServiceManagerTestCase
{

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new UnionAdmin();
        $coll = $obj->getManagedUnions();

        $newObj = clone $obj;
        $this->assertNotSame($coll, $newObj->getManagedUnions());
    }

    public function testSetManagedUnions()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();
        $union2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union2);

        $newCollection = new ArrayCollection();
        $newCollection->add($union0);
        $newCollection->add($union1);

        // Do the add
        $obj->setManagedUnions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedUnions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedUnions());
        $this->assertEquals(2, $obj->getManagedUnions()->count());
        $this->assertTrue($obj->getManagedUnions()->contains($union0));
        $this->assertTrue($obj->getManagedUnions()->contains($union1));
        $this->assertFalse($obj->getManagedUnions()->contains($union2));
    }

    public function testAddManagedUnions()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();
        $union2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union2);

        $newCollection = new ArrayCollection();
        $newCollection->add($union0);
        $newCollection->add($union1);

        // Do the add
        $obj->addManagedUnions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedUnions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedUnions());
        $this->assertEquals(3, $obj->getManagedUnions()->count());
        $this->assertTrue($obj->getManagedUnions()->contains($union0));
        $this->assertTrue($obj->getManagedUnions()->contains($union1));
        $this->assertTrue($obj->getManagedUnions()->contains($union2));
    }

    public function testAddManagedUnion()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union0);

        // Do teh add
        $obj->addManagedUnion($union1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedUnions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedUnions());
        $this->assertEquals(2, $obj->getManagedUnions()->count());
    }

    public function testHasManagedUnion()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();

        // Add roles to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union0);

        $this->assertTrue($obj->hasManagedUnion($union0));
        $this->assertFalse($obj->hasManagedUnion($union1));
    }

    public function testRemoveManagedUnion()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();

        // Add both to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union0);
        $collection->add($union1);

        // Do the remove of 0
        $obj->removeManagedUnion($union0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedUnions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedUnions());
        $this->assertEquals(1, $obj->getManagedUnions()->count());
        $this->assertFalse($obj->getManagedUnions()->contains($union0));
        $this->assertTrue($obj->getManagedUnions()->contains($union1));
    }

    public function testRemoveManagedUnions()
    {
        $obj = new UnionAdmin();

        $union0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union0->shouldIgnoreMissing();
        $union1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union1->shouldIgnoreMissing();
        $union2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        $union2->shouldIgnoreMissing();

        // Add them all to the existing collection
        $collection = $obj->getManagedUnions();
        $collection->add($union0);
        $collection->add($union1);
        $collection->add($union2);

        $newCollection = new ArrayCollection();
        $newCollection->add($union0);
        $newCollection->add($union1);

        // Do the remove of 0 and 1
        $obj->removeManagedUnions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedUnions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedUnions());
        $this->assertEquals(1, $obj->getManagedUnions()->count());
        $this->assertFalse($obj->getManagedUnions()->contains($union0));
        $this->assertFalse($obj->getManagedUnions()->contains($union1));
        $this->assertTrue($obj->getManagedUnions()->contains($union2));
    }

    public function testGetDiscriminator()
    {
        $obj = new UnionAdmin();
        $this->assertEquals('union_admin', $obj->getDiscriminator());
    }
}
