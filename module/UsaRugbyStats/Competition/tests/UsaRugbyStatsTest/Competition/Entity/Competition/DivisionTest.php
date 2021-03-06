<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class DivisionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new Division();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetName()
    {
        $obj = new Division();
        $this->assertNull($obj->getName());
        $obj->setName('Testing 123');
        $this->assertEquals('Testing 123', $obj->getName());
    }

    public function testCanBeConvertedToString()
    {
        $obj = new Division();
        $this->assertTrue(method_exists($obj, '__toString'));
        (string) $obj;
    }

    public function testConstructorInitializesCollections()
    {
        $obj = new Division();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
    }

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new Division();
        $coll = $obj->getTeamMemberships();

        $newObj = clone $obj;
        $this->assertNotSame($coll, $newObj->getTeamMemberships());
    }

    public function testGetSetCompetition()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        $obj = new Division();

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(null);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetCompetitionUpdatesCompetitionAssociationOfContainedTeamMemberships()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team->shouldReceive('setCompetition')->once()->withArgs([$comp]);
        $team->shouldReceive('setCompetition')->once()->withArgs([null]);

        $obj = new Division();
        $obj->getTeamMemberships()->add($team);

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(null);
        $this->assertNull($obj->getCompetition());
    }

    public function testSetTeamMemberships()
    {
        $obj = new Division();
        $collection = $obj->getTeamMemberships();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team1->shouldReceive('setCompetition')->once()->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);

        // Do the add
        $obj->setTeamMemberships($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(2, $obj->getTeamMemberships()->count());
    }

    public function testAddTeamMembership()
    {
        $obj = new Division();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->never();
        $team0->shouldReceive('setCompetition')->never();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team1->shouldReceive('setCompetition')->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);

        // Do teh add
        $obj->addTeamMembership($team1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(2, $obj->getTeamMemberships()->count());
    }

    public function testAddTeamMembershipDoesNotAllowDuplicates()
    {
        $obj = new Division();
        $collection = $obj->getTeamMemberships();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->once()->andReturnSelf();

        // Add team0 twice
        $coll = new ArrayCollection();
        $coll->add($team0);
        $coll->add($team0);
        $obj->addTeamMemberships($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(1, $obj->getTeamMemberships()->count());
    }

    public function testAddTeamMemberships()
    {
        $obj = new Division();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->never();
        $team0->shouldReceive('setCompetition')->never();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team1->shouldReceive('setCompetition')->once()->andReturnSelf();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team2->shouldReceive('setDivision')->withArgs([$obj])->once()->andReturnSelf();
        $team2->shouldReceive('setCompetition')->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($team1);
        $coll->add($team2);
        $obj->addTeamMemberships($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(3, $obj->getTeamMemberships()->count());
    }

    public function testHasTeamMembership()
    {
        $obj = new Division();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');

        // Add roles to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);

        $this->assertTrue($obj->hasTeamMembership($team0));
        $this->assertFalse($obj->hasTeamMembership($team1));
    }

    public function testRemoveTeamMembership()
    {
        $obj = new Division();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->withArgs([null])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->withArgs([null])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->never();
        $team1->shouldReceive('setCompetition')->never();

        // Add to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);
        $collection->add($team1);

        // Do the remove
        $obj->removeTeamMembership($team0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(1, $obj->getTeamMemberships()->count());
        $this->assertFalse($obj->getTeamMemberships()->contains($team0));
        $this->assertTrue($obj->getTeamMemberships()->contains($team1));
    }

    public function testRemoveTeamMemberships()
    {
        $obj = new Division();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setDivision')->withArgs([null])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->withArgs([null])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->never();
        $team1->shouldReceive('setCompetition')->never();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team2->shouldReceive('setDivision')->withArgs([null])->once()->andReturnSelf();
        $team2->shouldReceive('setCompetition')->withArgs([null])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);
        $collection->add($team1);
        $collection->add($team2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($team0);
        $coll->add($team2);
        $obj->removeTeamMemberships($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        $this->assertSame($collection, $obj->getTeamMemberships());
        $this->assertEquals(1, $obj->getTeamMemberships()->count());
        $this->assertFalse($obj->getTeamMemberships()->contains($team0));
        $this->assertTrue($obj->getTeamMemberships()->contains($team1));
        $this->assertFalse($obj->getTeamMemberships()->contains($team2));
    }

}
