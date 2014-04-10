<?php
namespace UsaRugbyStatsTest\Competition\Entity;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class TeamTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorInitializesCollections()
    {
        $obj = new Team();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
    }
    
    public function testGetSetUnion()
    {
        $union = Mockery::mock('UsaRugbyStats\Competition\Entity\Union');
        
        $obj = new Team();
        
        // Test setting to an instance of Union class
        $obj->setUnion($union);
        $this->assertSame($union, $obj->getUnion());

        // Test setting to null (disassociate from union)
        $obj->setUnion(NULL);
        $this->assertNull($obj->getUnion());
    }
     
    public function testSetCompetitions()
    {
        $obj = new Team();
        $collection = $obj->getCompetitions();
        
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        
        $newCollection = new ArrayCollection();
        $newCollection->add($comp0);
        $newCollection->add($comp1);
        
        // Do the add
        $obj->setCompetitions($newCollection);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getCompetitions());
        $this->assertEquals(2, $obj->getCompetitions()->count());
    }
    
    public function testAddCompetition()
    {
        $obj = new Team();
    
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
    
        // Add one to the existing collection
        $collection = $obj->getCompetitions();
        $collection->add($comp0);
        
        // Do teh add
        $obj->addCompetition($comp1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getCompetitions());
        $this->assertEquals(2, $obj->getCompetitions()->count());
    }

    public function testAddCompetitions()
    {
        $obj = new Team();
    
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
            
        // Add one to the existing collection
        $collection = $obj->getCompetitions();
        $collection->add($comp0);
    
        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($comp1);
        $coll->add($comp2);
        $obj->addCompetitions($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getCompetitions());
        $this->assertEquals(3, $obj->getCompetitions()->count());
    }
    
    public function testHasCompetition()
    {
        $obj = new Team();
    
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
    
        // Add roles to the existing collection
        $collection = $obj->getCompetitions();
        $collection->add($comp0);
    
        $this->assertTrue($obj->hasCompetition($comp0));
        $this->assertFalse($obj->hasCompetition($comp1));
    }

    public function testRemoveCompetition()
    {
        $obj = new Team();
    
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
    
        // Add one to the existing collection
        $collection = $obj->getCompetitions();
        $collection->add($comp0);
        $collection->add($comp1);
    
        // Do the remove
        $obj->removeCompetition($comp0);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
        $this->assertSame($collection, $obj->getCompetitions());
        $this->assertEquals(1, $obj->getCompetitions()->count());
        $this->assertFalse($obj->getCompetitions()->contains($comp0));
        $this->assertTrue($obj->getCompetitions()->contains($comp1));
    }
    
    public function testRemoveCompetitions()
    {
        $obj = new Team();
    
        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
    
        // Add one to the existing collection
        $collection = $obj->getCompetitions();
        $collection->add($comp0);
        $collection->add($comp1);
        $collection->add($comp2);
    
        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($comp0);
        $coll->add($comp2);
        $obj->removeCompetitions($coll);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getCompetitions());
        $this->assertSame($collection, $obj->getCompetitions());
        $this->assertEquals(1, $obj->getCompetitions()->count());
        $this->assertFalse($obj->getCompetitions()->contains($comp0));
        $this->assertTrue($obj->getCompetitions()->contains($comp1));
        $this->assertFalse($obj->getCompetitions()->contains($comp2));
    }

}