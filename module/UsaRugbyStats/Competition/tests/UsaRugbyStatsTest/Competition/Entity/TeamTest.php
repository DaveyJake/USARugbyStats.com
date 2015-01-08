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

    public function testGetSetRemoteId()
    {
        $obj = new Team();
        $this->assertNull($obj->getRemoteId());
        $obj->setRemoteId(12345);
        $this->assertEquals(12345, $obj->getRemoteId());
    }

    public function testGetSetName()
    {
        $obj = new Team();
        $this->assertNull($obj->getName());
        $obj->setName('Testing 123');
        $this->assertEquals('Testing 123', $obj->getName());
    }

    public function testGetSetAbbreviation()
    {
        $obj = new Team();
        $this->assertNull($obj->getAbbreviation());
        $obj->setAbbreviation('Testing 123');
        $this->assertEquals('Testing 123', $obj->getAbbreviation());
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

    public function testGetSetCity()
    {
        $obj = new Team();
        $this->assertNull($obj->getCity());
        $obj->setCity('Someplace');
        $this->assertEquals('Someplace', $obj->getCity());
    }

    public function testSetCityToNull()
    {
        $obj = new Team();
        $obj->setCity(null);
        $this->assertEquals(null, $obj->getCity());
    }

    public function testGetSetState()
    {
        $obj = new Team();
        $this->assertNull($obj->getState());
        $obj->setState('AK');
        $this->assertEquals('AK', $obj->getState());
    }

    public function testSetStateToNull()
    {
        $obj = new Team();
        $obj->setState(null);
        $this->assertEquals(null, $obj->getState());
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

    public function testSetMembers()
    {
        $obj = new Team();
        $collection = $obj->getMembers();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($mbr0);
        $newCollection->add($mbr1);

        // Do the add
        $obj->setMembers($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMembers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMembers());
        $this->assertEquals(2, $obj->getMembers()->count());
    }

    public function testAddMember()
    {
        $obj = new Team();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setTeam')->never();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);

        // Do teh add
        $obj->addMember($mbr1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMembers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMembers());
        $this->assertEquals(2, $obj->getMembers()->count());
    }

    public function testAddMembers()
    {
        $obj = new Team();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setTeam')->never();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldReceive('setTeam')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($mbr1);
        $coll->add($mbr2);
        $obj->addMembers($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMembers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMembers());
        $this->assertEquals(3, $obj->getMembers()->count());
    }

    public function testHasMember()
    {
        $obj = new Team();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');

        // Add roles to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);

        $this->assertTrue($obj->hasMember($mbr0));
        $this->assertFalse($obj->hasMember($mbr1));
    }

    public function testHasMemberAccount()
    {
        $obj = new Team();

        $mockRole0 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $mockRole0->shouldReceive('getAccount')->andReturnNull();

        $account = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');
        $account->shouldReceive('getId')->andReturn(42);
        $mockRole1 = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $mockRole1->shouldReceive('getAccount')->andReturn($account);

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('getRole')->andReturn($mockRole0);
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('getRole')->andReturn($mockRole1);
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldReceive('getRole')->andReturnNull();

        // Add roles to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);
        $collection->add($mbr1);
        $collection->add($mbr2);

        $this->assertTrue($obj->hasMember($account));
    }

    public function testHasMemberUnknown()
    {
        $obj = new Team();
        $this->assertFalse($obj->hasMember('42'));
    }

    public function testRemoveMember()
    {
        $obj = new Team();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setTeam')->never();

        // Add one to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);
        $collection->add($mbr1);

        // Do the remove
        $obj->removeMember($mbr0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMembers());
        $this->assertSame($collection, $obj->getMembers());
        $this->assertEquals(1, $obj->getMembers()->count());
        $this->assertFalse($obj->getMembers()->contains($mbr0));
        $this->assertTrue($obj->getMembers()->contains($mbr1));
    }

    public function testRemoveMembers()
    {
        $obj = new Team();

        $mbr0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr0->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();
        $mbr1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr1->shouldReceive('setTeam')->never();
        $mbr2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $mbr2->shouldReceive('setTeam')->withArgs([null])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMembers();
        $collection->add($mbr0);
        $collection->add($mbr1);
        $collection->add($mbr2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($mbr0);
        $coll->add($mbr2);
        $obj->removeMembers($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMembers());
        $this->assertSame($collection, $obj->getMembers());
        $this->assertEquals(1, $obj->getMembers()->count());
        $this->assertFalse($obj->getMembers()->contains($mbr0));
        $this->assertTrue($obj->getMembers()->contains($mbr1));
        $this->assertFalse($obj->getMembers()->contains($mbr2));
    }

    public function testGetCompetitionsOfType()
    {
        $obj = new Team();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp0->shouldReceive('getCompetition->getType')->andReturn('F');

        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $comp1->shouldReceive('getCompetition->getType')->andReturn('P');

        // Add roles to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($comp0);
        $collection->add($comp1);

        $result = $obj->getCompetitionsOfType('P');
        $this->assertCount(1, $result);
        $this->assertSame($comp1, $result->current());

        $result = $obj->getCompetitionsOfType('L');
        $this->assertCount(0, $result);
    }
}
