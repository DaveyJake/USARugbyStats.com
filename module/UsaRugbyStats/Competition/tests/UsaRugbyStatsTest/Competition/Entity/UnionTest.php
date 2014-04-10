<?php
namespace UsaRugbyStatsTest\Competition\Entity;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Union;

class UnionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorInitializesCollections()
    {
        $obj = new Union();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getTeams());
    }
    
    public function testSetTeams()
    {
        $obj = new Union();
        $collection = $obj->getTeams();
        
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
        
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
        $obj = new Union();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
    
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
        $obj = new Union();
        $collection = $obj->getTeams();
        
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
        
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
        $obj = new Union();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->never();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2->shouldReceive('setUnion')->withArgs([$obj])->once()->andReturnSelf();
            
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
        $obj = new Union();
    
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
        $obj = new Union();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('setUnion')->never();
    
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
        $obj = new Union();
    
        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team0->shouldReceive('setUnion')->withArgs([NULL])->once()->andReturnSelf();
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1->shouldReceive('setUnion')->never();
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2->shouldReceive('setUnion')->withArgs([NULL])->once()->andReturnSelf();
    
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