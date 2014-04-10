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
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
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

    public function testSetTeams()
    {
        $obj = new Division();
        $collection = $obj->getTeams();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);
    
        // Do the add
        $obj->setTeams($newCollection);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(2, $obj->getTeams()->count());
    }
    
    public function testAddTeam()
    {
        $obj = new Division();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add one to the existing collection
        $collection = $obj->getTeams();
        $collection->add($team0);
    
        // Do teh add
        $obj->addTeam($team1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(2, $obj->getTeams()->count());
    }
    
    public function testAddTeamDoesNotAllowDuplicates()
    {
        $obj = new Division();
        $collection = $obj->getTeams();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add team0 twice
        $coll = new ArrayCollection();
        $coll->add($team0);
        $coll->add($team0);
        $obj->addTeams($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(1, $obj->getTeams()->count());
    }
    
    public function testAddTeams()
    {
        $obj = new Division();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add one to the existing collection
        $collection = $obj->getTeams();
        $collection->add($team0);
    
        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($team1);
        $coll->add($team2);
        $obj->addTeams($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(3, $obj->getTeams()->count());
    }
    
    public function testHasTeam()
    {
        $obj = new Division();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add roles to the existing collection
        $collection = $obj->getTeams();
        $collection->add($team0);
    
        $this->assertTrue($obj->hasTeam($team0));
        $this->assertFalse($obj->hasTeam($team1));
    }
    
    public function testRemoveTeam()
    {
        $obj = new Division();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add to the existing collection
        $collection = $obj->getTeams();
        $collection->add($team0);
        $collection->add($team1);
    
        // Do the remove
        $obj->removeTeam($team0);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(1, $obj->getTeams()->count());
        $this->assertFalse($obj->getTeams()->contains($team0));
        $this->assertTrue($obj->getTeams()->contains($team1));
    }
    
    public function testRemoveTeams()
    {
        $obj = new Division();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        // Add one to the existing collection
        $collection = $obj->getTeams();
        $collection->add($team0);
        $collection->add($team1);
        $collection->add($team2);
    
        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($team0);
        $coll->add($team2);
        $obj->removeTeams($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
        $this->assertSame($collection, $obj->getTeams());
        $this->assertEquals(1, $obj->getTeams()->count());
        $this->assertFalse($obj->getTeams()->contains($team0));
        $this->assertTrue($obj->getTeams()->contains($team1));
        $this->assertFalse($obj->getTeams()->contains($team2));
    }

}