<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use Doctrine\Common\Collections\ArrayCollection;

class MemberTest extends ServiceManagerTestCase
{

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new Member();
        $coll = $obj->getMemberships();

        $newObj = clone $obj;
        $this->assertNotSame($coll, $newObj->getMemberships());
    }

    public function testSetMemberships()
    {
        $obj = new Member();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('getName')->andReturn('Team A');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('getName')->andReturn('Team B');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2->shouldReceive('getName')->andReturn('Team C');

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setRole')->withArgs([$obj]);
        $mbr0->shouldReceive('getTeam')->andReturn($team0);
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setRole')->withArgs([$obj]);
        $mbr1->shouldReceive('getTeam')->andReturn($team1);
        $mbr1->shouldIgnoreMissing();
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldReceive('getTeam')->andReturn($team2);
        $mbr2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr2);

        $newCollection = new ArrayCollection();
        $newCollection->add($mbr0);
        $newCollection->add($mbr1);

        // Do the add
        $obj->setMemberships($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMemberships());
        $this->assertEquals(2, $obj->getMemberships()->count());
        $this->assertTrue($obj->getMemberships()->contains($mbr0));
        $this->assertTrue($obj->getMemberships()->contains($mbr1));
        $this->assertFalse($obj->getMemberships()->contains($mbr2));
    }

    public function testAddMemberships()
    {
        $obj = new Member();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('getName')->andReturn('Team A');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('getName')->andReturn('Team B');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2->shouldReceive('getName')->andReturn('Team C');

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setRole')->withArgs([$obj]);
        $mbr0->shouldReceive('getTeam')->andReturn($team0);
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setRole')->withArgs([$obj]);
        $mbr1->shouldReceive('getTeam')->andReturn($team1);
        $mbr1->shouldIgnoreMissing();
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldReceive('getTeam')->andReturn($team2);
        $mbr2->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr2);

        $newCollection = new ArrayCollection();
        $newCollection->add($mbr0);
        $newCollection->add($mbr1);

        // Do the add
        $obj->addMemberships($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMemberships());
        $this->assertEquals(3, $obj->getMemberships()->count());
        $this->assertTrue($obj->getMemberships()->contains($mbr0));
        $this->assertTrue($obj->getMemberships()->contains($mbr1));
        $this->assertTrue($obj->getMemberships()->contains($mbr2));
    }

    public function testAddMembership()
    {
        $obj = new Member();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('getName')->andReturn('Team A');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('getName')->andReturn('Team B');

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('getTeam')->andReturn($team0);
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setRole')->withArgs([$obj]);
        $mbr1->shouldReceive('getTeam')->andReturn($team1);
        $mbr1->shouldIgnoreMissing();

        // Add one to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr0);

        // Do teh add
        $obj->addMembership($mbr1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMemberships());
        $this->assertEquals(2, $obj->getMemberships()->count());
    }

    public function testHasMembership()
    {
        $obj = new Member();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldIgnoreMissing();

        // Add roles to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr0);

        $this->assertTrue($obj->hasMembership($mbr0));
        $this->assertFalse($obj->hasMembership($mbr1));
    }

    public function testRemoveMembership()
    {
        $obj = new Member();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setRole')->withArgs([null]);
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldIgnoreMissing();

        // Add both to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr0);
        $collection->add($mbr1);

        // Do the remove of 0
        $obj->removeMembership($mbr0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMemberships());
        $this->assertEquals(1, $obj->getMemberships()->count());
        $this->assertFalse($obj->getMemberships()->contains($mbr0));
        $this->assertTrue($obj->getMemberships()->contains($mbr1));
    }

    public function testRemoveMemberships()
    {
        $obj = new Member();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setRole')->withArgs([null]);
        $mbr0->shouldIgnoreMissing();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setRole')->withArgs([null]);
        $mbr1->shouldIgnoreMissing();
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldIgnoreMissing();

        // Add them all to the existing collection
        $collection = $obj->getMemberships();
        $collection->add($mbr0);
        $collection->add($mbr1);
        $collection->add($mbr2);

        $newCollection = new ArrayCollection();
        $newCollection->add($mbr0);
        $newCollection->add($mbr1);

        // Do the remove of 0 and 1
        $obj->removeMemberships($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMemberships());
        $this->assertEquals(1, $obj->getMemberships()->count());
        $this->assertFalse($obj->getMemberships()->contains($mbr0));
        $this->assertFalse($obj->getMemberships()->contains($mbr1));
        $this->assertTrue($obj->getMemberships()->contains($mbr2));
    }

    public function testGetDiscriminator()
    {
        $obj = new Member();
        $this->assertEquals('member', $obj->getDiscriminator());
    }
}
