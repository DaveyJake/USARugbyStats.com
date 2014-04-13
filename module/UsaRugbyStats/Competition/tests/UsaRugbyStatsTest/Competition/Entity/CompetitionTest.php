<?php
namespace UsaRugbyStatsTest\Competition\Entity;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorInitializesCollections()
    {
        $obj = new Competition();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
    }
     
    public function testSetDivisions()
    {
        $obj = new Competition();
        $collection = $obj->getDivisions();
        
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        
        $newCollection = new ArrayCollection();
        $newCollection->add($div0);
        $newCollection->add($div1);
        
        // Do the add
        $obj->setDivisions($newCollection);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(2, $obj->getDivisions()->count());
    }
    
    public function testAddDivision()
    {
        $obj = new Competition();
    
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div0->shouldReceive('setCompetition')->never();
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getDivisions();
        $collection->add($div0);
        
        // Do teh add
        $obj->addDivision($div1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(2, $obj->getDivisions()->count());
    }

    public function testAddDivisionDoesNotAllowDuplicates()
    {
        $obj = new Competition();
        $collection = $obj->getDivisions();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $team0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        // Add team0 twice
        $coll = new ArrayCollection();
        $coll->add($team0);
        $coll->add($team0);
        $obj->addDivisions($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeamMemberships());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(1, $obj->getDivisions()->count());
    }

    public function testAddDivisions()
    {
        $obj = new Competition();
    
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div0->shouldReceive('setCompetition')->never();
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $div2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div2->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
            
        // Add one to the existing collection
        $collection = $obj->getDivisions();
        $collection->add($div0);
    
        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($div1);
        $coll->add($div2);
        $obj->addDivisions($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(3, $obj->getDivisions()->count());
    }
    
    public function testHasDivision()
    {
        $obj = new Competition();
    
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
    
        // Add roles to the existing collection
        $collection = $obj->getDivisions();
        $collection->add($div0);
    
        $this->assertTrue($obj->hasDivision($div0));
        $this->assertFalse($obj->hasDivision($div1));
    }

    public function testRemoveDivision()
    {
        $obj = new Competition();
    
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getDivisions();
        $collection->add($div0);
        $collection->add($div1);
    
        // Do the remove
        $obj->removeDivision($div0);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(1, $obj->getDivisions()->count());
        $this->assertFalse($obj->getDivisions()->contains($div0));
        $this->assertTrue($obj->getDivisions()->contains($div1));
    }
    
    public function testRemoveDivisions()
    {
        $obj = new Competition();
    
        $div0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $div1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div1->shouldReceive('setCompetition')->never();
        $div2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div2->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getDivisions();
        $collection->add($div0);
        $collection->add($div1);
        $collection->add($div2);
    
        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($div0);
        $coll->add($div2);
        $obj->removeDivisions($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getDivisions());
        $this->assertSame($collection, $obj->getDivisions());
        $this->assertEquals(1, $obj->getDivisions()->count());
        $this->assertFalse($obj->getDivisions()->contains($div0));
        $this->assertTrue($obj->getDivisions()->contains($div1));
        $this->assertFalse($obj->getDivisions()->contains($div2));
    }


    public function testSetTeamMemberships()
    {
        $obj = new Competition();
        $collection = $obj->getTeamMemberships();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
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
        $obj = new Competition();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
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
        $obj = new Competition();
        $collection = $obj->getTeamMemberships();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
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
        $obj = new Competition();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->never();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team2->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
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
        $obj = new Competition();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
    
        // Add roles to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($team0);
    
        $this->assertTrue($obj->hasTeamMembership($team0));
        $this->assertFalse($obj->hasTeamMembership($team1));
    }

    public function testHasTeam()
    {
        $obj = new Competition();
        
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('getId')->twice()->andReturn(42);
        
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('getId')->twice()->andReturn(99);
    
        $teamMembership = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $teamMembership->shouldReceive('getTeam')->once()->andReturn($team0);
    
        // Add roles to the existing collection
        $collection = $obj->getTeamMemberships();
        $collection->add($teamMembership);
    
        $this->assertTrue($obj->hasTeam($team0));
        $this->assertFalse($obj->hasTeam($team1));
    }
    
    public function testRemoveTeamMembership()
    {
        $obj = new Competition();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
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
        $obj = new Competition();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
        $team1->shouldReceive('setCompetition')->never();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');
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

    public function testSetGames()
    {
        $obj = new Competition();
        $collection = $obj->getGames();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        $newCollection = new ArrayCollection();
        $newCollection->add($game0);
        $newCollection->add($game1);
    
        // Do the add
        $obj->setGames($newCollection);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(2, $obj->getGames()->count());
    }
    
    public function testAddGame()
    {
        $obj = new Competition();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getGames();
        $collection->add($game0);
    
        // Do teh add
        $obj->addGame($game1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(2, $obj->getGames()->count());
    }
    
    public function testAddGameDoesNotAllowDuplicates()
    {
        $obj = new Competition();
        $collection = $obj->getGames();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        // Add game0 twice
        $coll = new ArrayCollection();
        $coll->add($game0);
        $coll->add($game0);
        $obj->addGames($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(1, $obj->getGames()->count());
    }
    
    public function testAddGames()
    {
        $obj = new Competition();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->never();
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
        $game2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game2->shouldReceive('setCompetition')->withArgs([$obj])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getGames();
        $collection->add($game0);
    
        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($game1);
        $coll->add($game2);
        $obj->addGames($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(3, $obj->getGames()->count());
    }
    
    public function testHasGame()
    {
        $obj = new Competition();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
    
        // Add roles to the existing collection
        $collection = $obj->getGames();
        $collection->add($game0);
    
        $this->assertTrue($obj->hasGame($game0));
        $this->assertFalse($obj->hasGame($game1));
    }
    
    public function testRemoveGame()
    {
        $obj = new Competition();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1->shouldReceive('setCompetition')->never();
    
        // Add to the existing collection
        $collection = $obj->getGames();
        $collection->add($game0);
        $collection->add($game1);
    
        // Do the remove
        $obj->removeGame($game0);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(1, $obj->getGames()->count());
        $this->assertFalse($obj->getGames()->contains($game0));
        $this->assertTrue($obj->getGames()->contains($game1));
    }
    
    public function testRemoveGames()
    {
        $obj = new Competition();
    
        $game0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game0->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
        $game1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game1->shouldReceive('setCompetition')->never();
        $game2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Game');
        $game2->shouldReceive('setCompetition')->withArgs([NULL])->once()->andReturnSelf();
    
        // Add one to the existing collection
        $collection = $obj->getGames();
        $collection->add($game0);
        $collection->add($game1);
        $collection->add($game2);
    
        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($game0);
        $coll->add($game2);
        $obj->removeGames($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getGames());
        $this->assertSame($collection, $obj->getGames());
        $this->assertEquals(1, $obj->getGames()->count());
        $this->assertFalse($obj->getGames()->contains($game0));
        $this->assertTrue($obj->getGames()->contains($game1));
        $this->assertFalse($obj->getGames()->contains($game2));
    }
}