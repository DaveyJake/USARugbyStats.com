<?php
namespace UsaRugbyStatsTest\Competition\Entity;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class TeamTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new Team();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetName()
    {
        $obj = new Team();
        $this->assertNull($obj->getName());
        $obj->setName('Testing 123');
        $this->assertEquals('Testing 123', $obj->getName());
    }

    public function testGetSetEmail()
    {
        $obj = new Team();
        $this->assertNull($obj->getEmail());
        $obj->setEmail('test@test.com');
        $this->assertEquals('test@test.com', $obj->getEmail());
    }

    public function testGetSetWebsite()
    {
        $obj = new Team();
        $this->assertNull($obj->getWebsite());
        $obj->setWebsite('http://www.test.com');
        $this->assertEquals('http://www.test.com', $obj->getWebsite());
    }

    public function testGetSetFacebookHandle()
    {
        $obj = new Team();
        $this->assertNull($obj->getFacebookHandle());
        $obj->setFacebookHandle('testtest');
        $this->assertEquals('testtest', $obj->getFacebookHandle());
    }

    public function testGetSetTwitterHandle()
    {
        $obj = new Team();
        $this->assertNull($obj->getTwitterHandle());
        $obj->setTwitterHandle('testtesttest');
        $this->assertEquals('testtesttest', $obj->getTwitterHandle());
    }

    public function testCanBeConvertedToString()
    {
        $obj = new Team();
        $this->assertTrue(method_exists($obj, '__toString'));
        (string) $obj;
    }

    public function testConstructorInitializesCollections()
    {
        $obj = new Team();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
    }

    public function testCollectionsAreReplacedOnObjectClone()
    {
        $obj = new Team();
        $collection = $obj->getTeamMemberships();
        $obj2 = clone $obj;
        $this->assertNotSame($obj2->getTeamMemberships(), $collection);
    }

    public function testGetSetUnion()
    {
        $union = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');

        $obj = new Team();

        // Test setting to an instance of Union class
        $obj->setUnion($union);
        $this->assertSame($union, $obj->getUnion());

        // Test setting to null (disassociate from union)
        $obj->setUnion(null);
        $this->assertNull($obj->getUnion());
    }

    public function testSetTeamMemberships()
    {
        $obj = new Team();
        $collection = $obj->getTeamMemberships();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($comp0);
        $newCollection->add($comp1);

        // Do the add
        $obj->setTeamMemberships($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(2, $obj->getTeamMemberships()->count());
    }

    public function testAddTeamMembership()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('setTeam')->never();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);

        // Do teh add
        $obj->addTeamMembership($comp1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(2, $obj->getTeamMemberships()->count());
    }

    public function testAddTeamMemberships()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('setTeam')->never();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp2->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($comp1);
        $coll->add($comp2);
        $obj->addTeamMemberships($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(3, $obj->getTeamMemberships()->count());
    }

    public function testHasTeamMembership()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');

        // Add roles to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);

        $this->assertTrue($obj->hasTeamMembership($comp0));
        $this->assertFalse($obj->hasTeamMembership($comp1));
    }

    public function testRemoveTeamMembership()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('setTeam')->never();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);
        $collection->add($comp1);

        // Do the remove
        $obj->removeTeamMembership($comp0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(1, $obj->getTeamMemberships()->count());
        $this->assertFalse($obj->getTeamMemberships()->contains($comp0));
        $this->assertTrue($obj->getTeamMemberships()->contains($comp1));
    }

    public function testRemoveTeamMemberships()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('setTeam')->never();
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp2->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);
        $collection->add($comp1);
        $collection->add($comp2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($comp0);
        $coll->add($comp2);
        $obj->removeTeamMemberships($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(1, $obj->getTeamMemberships()->count());
        $this->assertFalse($obj->getTeamMemberships()->contains($comp0));
        $this->assertTrue($obj->getTeamMemberships()->contains($comp1));
        $this->assertFalse($obj->getTeamMemberships()->contains($comp2));
    }

}
