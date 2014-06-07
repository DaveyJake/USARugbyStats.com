<?php
namespace UsaRugbyStatsTest\Competition\Entity;

use Mockery;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new Location();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetName()
    {
        $obj = new Location();
        $this->assertNull($obj->getName());
        $obj->setName('Testing 123');
        $this->assertEquals('Testing 123', $obj->getName());
    }

    public function testCanBeConvertedToString()
    {
        $obj = new Location();
        $this->assertTrue(method_exists($obj, '__toString'));
        (string) $obj;
    }

    public function testGetSetAddress()
    {
        $obj = new Location();
        $this->assertEmpty($obj->getAddress());
        $obj->setAddress('125 Some Street');
        $this->assertEquals('125 Some Street', $obj->getAddress());
    }

    public function testGetSetCoordinates()
    {
        $obj = new Location();
        $this->assertEmpty($obj->getCoordinates());
        $obj->setCoordinates('47.565094, -52.715061');
        $this->assertEquals('47.565094, -52.715061', $obj->getCoordinates());
    }

    public function testGetSetCoordinatesAcceptsEmptyString()
    {
        $obj = new Location();
        $obj->setCoordinates('');
        $this->assertEmpty($obj->getCoordinates());
    }

    public function testGetSetCoordinatesRejectsInvalidCoordinatesString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $obj = new Location();
        $obj->setCoordinates('dfasdfasfasf');
    }

    public function testGetSetCoordinatesRejectsOutOfBoundsCoordinatesString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $obj = new Location();
        $obj->setCoordinates('100, 300');
    }

    public function testConstructorInitializesCollections()
    {
        $obj = new Location();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
    }

    public function testCollectionsAreReplacedOnObjectClone()
    {
        $obj = new Location();
        $collection = $obj->getMatches();
        $obj2 = clone $obj;
        $this->assertNotSame($obj2->getMatches(), $collection);
    }

    public function testSetMatches()
    {
        $obj = new Location();
        $collection = $obj->getMatches();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp0->shouldReceive('setLocation')->withArgs([$obj])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1->shouldReceive('setLocation')->withArgs([$obj])->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($comp0);
        $newCollection->add($comp1);

        // Do the add
        $obj->setMatches($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMatches());
        $this->assertEquals(2, $obj->getMatches()->count());
    }

    public function testAddMatch()
    {
        $obj = new Location();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp0->shouldReceive('setLocation')->never();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1->shouldReceive('setLocation')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMatches();
        $collection->add($comp0);

        // Do teh add
        $obj->addMatch($comp1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMatches());
        $this->assertEquals(2, $obj->getMatches()->count());
    }

    public function testAddMatches()
    {
        $obj = new Location();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp0->shouldReceive('setLocation')->never();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1->shouldReceive('setLocation')->withArgs([$obj])->andReturnSelf();
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp2->shouldReceive('setLocation')->withArgs([$obj])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMatches();
        $collection->add($comp0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($comp1);
        $coll->add($comp2);
        $obj->addMatches($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getMatches());
        $this->assertEquals(3, $obj->getMatches()->count());
    }

    public function testHasMatch()
    {
        $obj = new Location();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');

        // Add roles to the existing collection
        $collection = $obj->getMatches();
        $collection->add($comp0);

        $this->assertTrue($obj->hasMatch($comp0));
        $this->assertFalse($obj->hasMatch($comp1));
    }

    public function testRemoveMatch()
    {
        $obj = new Location();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp0->shouldReceive('setLocation')->withArgs([NULL])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1->shouldReceive('setLocation')->never();

        // Add one to the existing collection
        $collection = $obj->getMatches();
        $collection->add($comp0);
        $collection->add($comp1);

        // Do the remove
        $obj->removeMatch($comp0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
        $this->assertSame($collection, $obj->getMatches());
        $this->assertEquals(1, $obj->getMatches()->count());
        $this->assertFalse($obj->getMatches()->contains($comp0));
        $this->assertTrue($obj->getMatches()->contains($comp1));
    }

    public function testRemoveMatches()
    {
        $obj = new Location();

        $comp0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp0->shouldReceive('setLocation')->withArgs([NULL])->andReturnSelf();
        $comp1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp1->shouldReceive('setLocation')->never();
        $comp2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $comp2->shouldReceive('setLocation')->withArgs([NULL])->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getMatches();
        $collection->add($comp0);
        $collection->add($comp1);
        $collection->add($comp2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($comp0);
        $coll->add($comp2);
        $obj->removeMatches($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getMatches());
        $this->assertSame($collection, $obj->getMatches());
        $this->assertEquals(1, $obj->getMatches()->count());
        $this->assertFalse($obj->getMatches()->contains($comp0));
        $this->assertTrue($obj->getMatches()->contains($comp1));
        $this->assertFalse($obj->getMatches()->contains($comp2));
    }

}
