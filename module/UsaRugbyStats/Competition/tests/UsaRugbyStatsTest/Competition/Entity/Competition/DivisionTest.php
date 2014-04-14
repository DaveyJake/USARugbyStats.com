<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class DivisionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorInitializesCollections()
    {
        $obj = new Division();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
    }

    public function testGetSetCompetition()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        $obj = new Division();

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(NULL);
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
        $team0->shouldReceive('setDivision')->withArgs([NULL])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->withArgs([NULL])->once()->andReturnSelf();
        $team1->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();

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
        $team0->shouldReceive('setDivision')->withArgs([NULL])->once()->andReturnSelf();
        $team0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setDivision')->never();
        $team1->shouldReceive('setCompetition')->never();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team2->shouldReceive('setDivision')->withArgs([NULL])->once()->andReturnSelf();
        $team2->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();

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
