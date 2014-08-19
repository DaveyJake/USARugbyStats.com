<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use Doctrine\Common\Collections\ArrayCollection;

class CompetitionAdminTest extends ServiceManagerTestCase
{

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new CompetitionAdmin();
        $coll = $obj->getManagedCompetitions();

        $newObj = clone $obj;
        $this->assertNotSame($coll, $newObj->getManagedCompetitions());
    }

    public function testSetManagedCompetitions()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();
        $competition2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition2);

        $newCollection = new ArrayCollection();
        $newCollection->add($competition0);
        $newCollection->add($competition1);

        // Do the add
        $obj->setManagedCompetitions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedCompetitions());
        $this->assertEquals(2, $obj->getManagedCompetitions()->count());
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition0));
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition1));
        $this->assertFalse($obj->getManagedCompetitions()->contains($competition2));
    }

    public function testAddManagedCompetitions()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();
        $competition2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition2);

        $newCollection = new ArrayCollection();
        $newCollection->add($competition0);
        $newCollection->add($competition1);

        // Do the add
        $obj->addManagedCompetitions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedCompetitions());
        $this->assertEquals(3, $obj->getManagedCompetitions()->count());
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition0));
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition1));
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition2));
    }

    public function testAddManagedCompetition()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition0);

        // Do teh add
        $obj->addManagedCompetition($competition1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedCompetitions());
        $this->assertEquals(2, $obj->getManagedCompetitions()->count());
    }

    public function testHasManagedCompetition()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();

        // Add roles to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition0);

        $this->assertTrue($obj->hasManagedCompetition($competition0));
        $this->assertFalse($obj->hasManagedCompetition($competition1));
    }

    public function testRemoveManagedCompetition()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();

        // Add both to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition0);
        $collection->add($competition1);

        // Do the remove of 0
        $obj->removeManagedCompetition($competition0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedCompetitions());
        $this->assertEquals(1, $obj->getManagedCompetitions()->count());
        $this->assertFalse($obj->getManagedCompetitions()->contains($competition0));
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition1));
    }

    public function testRemoveManagedCompetitions()
    {
        $obj = new CompetitionAdmin();

        $competition0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition0->shouldIgnoreMissing();
        $competition1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition1->shouldIgnoreMissing();
        $competition2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition2->shouldIgnoreMissing();

        // Add them all to the existing collection
        $collection = $obj->getManagedCompetitions();
        $collection->add($competition0);
        $collection->add($competition1);
        $collection->add($competition2);

        $newCollection = new ArrayCollection();
        $newCollection->add($competition0);
        $newCollection->add($competition1);

        // Do the remove of 0 and 1
        $obj->removeManagedCompetitions($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedCompetitions());
        $this->assertEquals(1, $obj->getManagedCompetitions()->count());
        $this->assertFalse($obj->getManagedCompetitions()->contains($competition0));
        $this->assertFalse($obj->getManagedCompetitions()->contains($competition1));
        $this->assertTrue($obj->getManagedCompetitions()->contains($competition2));
    }

    public function testGetDiscriminator()
    {
        $obj = new CompetitionAdmin();
        $this->assertEquals('competition_admin', $obj->getDiscriminator());
    }
}
