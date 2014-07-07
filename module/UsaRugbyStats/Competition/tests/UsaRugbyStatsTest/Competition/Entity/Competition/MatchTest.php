<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Location;

class MatchTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new Match();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetDescription()
    {
        $obj = new Match();
        $this->assertNull($obj->getDescription());
        $obj->setDescription('Testing 123');
        $this->assertEquals('Testing 123', $obj->getDescription());
    }

    public function testCanBeConvertedToString()
    {
        $homeTeam = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $homeTeam->shouldReceive('getTeam->getName')->andReturn('Home');
        $homeTeam->shouldReceive('setType')->andReturnSelf();
        $homeTeam->shouldReceive('getType')->andReturn('H');
        $homeTeam->shouldReceive('setMatch')->andReturnSelf();

        $awayTeam = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $awayTeam->shouldReceive('getTeam->getName')->andReturn('Away');
        $awayTeam->shouldReceive('setType')->andReturnSelf();
        $awayTeam->shouldReceive('getType')->andReturn('A');
        $awayTeam->shouldReceive('setMatch')->andReturnSelf();

        $obj = new Match();
        $obj->setHomeTeam($homeTeam);
        $obj->setAwayTeam($awayTeam);

        $this->assertTrue(method_exists($obj, '__toString'));
        $this->assertEquals('Home v. Away', (string) $obj);
    }

    public function testConstructorInitializesCollections()
    {
        $obj = new Match();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
    }

    public function testCollectionsAreReplacedOnObjectClone()
    {
        $obj = new Match();
        $signatures = $obj->getSignatures();
        $events = $obj->getEvents();

        $obj2 = clone $obj;
        $this->assertNotSame($obj2->getSignatures(), $signatures);
        $this->assertNotSame($obj2->getEvents(), $events);
    }

    public function testGetSetCompetition()
    {
        $obj = new Match();

        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(null);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetLocation()
    {
        $location = new Location();
        $obj = new Match();
        $obj->setLocation($location);

        $this->assertSame($location, $obj->getLocation());
    }

    public function testLocationIsNullable()
    {
        $obj = new Match();
        $obj->setLocation(null);

        $this->assertNull($obj->getLocation());
    }

    public function testGetSetHomeTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['H'])->once()->andReturnSelf();
        $team0->shouldReceive('getType')->andReturn('H');

        $obj->setHomeTeam($team0);

        $this->assertSame($team0, $obj->getHomeTeam());
    }

    public function testSetHomeTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setHomeTeam(null);
    }

    public function testGetSetAwayTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['A'])->once()->andReturnSelf();
        $team0->shouldReceive('getType')->andReturn('A');

        $obj->setAwayTeam($team0);

        $this->assertSame($team0, $obj->getAwayTeam());
    }

    public function testSetAwayTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setAwayTeam(null);
    }

    public function testGetSetDate()
    {
        $dt = new \DateTime();

        $obj = new Match();
        $this->assertNull($obj->getDate());
        $obj->setDate($dt);
        $this->assertSame($dt, $obj->getDate());
    }

    public function testGetSetDateOnlyAcceptsDateTimeObjects()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setDate('now');
    }

    public function testSetDateDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setDate(null);
    }

    /**
     * @dataProvider providerGetSetStatus
     */
    public function testGetSetStatus($status, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new Match();
        $obj->setStatus($status);
        $this->assertEquals($status, $obj->getStatus());
    }

    /**
     * Data Provider for testGetSetPosition (lists valid Match statuses)
     *
     * @return array
     */
    public function providerGetSetStatus()
    {
        return [
            [ 'NS', true ],
            [ 'S', true ],
            [ 'F', true ],
            [ 'HF', true ],
            [ 'AF', true ],
            [ 'C', true ],
            [ 'XX', false ],
            [ null, false ],
        ];
    }

    public function testStatusDefaultIsNotStarted()
    {
        $obj = new Match();
        $this->assertEquals('NS', $obj->getStatus());
    }

    public function testSetSignatures()
    {
        $obj = new Match();
        $collection = $obj->getSignatures();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($sig0);
        $newCollection->add($sig1);

        // Do the add
        $obj->setSignatures($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(2, $obj->getSignatures()->count());
    }

    public function testAddSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->never();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        // Do teh add
        $obj->addSignature($sig1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(2, $obj->getSignatures()->count());
    }

    public function testAddSignatureWillOverwriteExistingSignatureOfSameType()
    {
        $obj = new Match();
        $collection = $obj->getSignatures();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        // Add signature0 twice
        $coll = new ArrayCollection();
        $coll->add($sig0);
        $coll->add($sig0);
        $obj->addSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
    }

    public function testAddSignatures()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->never();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $sig2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig2->shouldReceive('getType')->andReturn('REF');
        $sig2->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($sig1);
        $coll->add($sig2);
        $obj->addSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(3, $obj->getSignatures()->count());
    }

    public function testHasSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');

        // Add roles to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        $this->assertTrue($obj->hasSignature($sig0));
        $this->assertFalse($obj->hasSignature($sig1));
    }

    public function testHasSignatureAcceptsStringArgument()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');

        // Add roles to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        $this->assertTrue($obj->hasSignature('HC'));
        $this->assertFalse($obj->hasSignature('AC'));
    }

    public function testRemoveSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('setMatch')->never();

        // Add to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);
        $collection->add($sig1);

        // Do the remove
        $obj->removeSignature($sig0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
        $this->assertFalse($obj->getSignatures()->contains($sig0));
        $this->assertTrue($obj->getSignatures()->contains($sig1));
    }

    public function testRemoveSignatures()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('setMatch')->never();
        $sig2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig2->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);
        $collection->add($sig1);
        $collection->add($sig2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($sig0);
        $coll->add($sig2);
        $obj->removeSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
        $this->assertFalse($obj->getSignatures()->contains($sig0));
        $this->assertTrue($obj->getSignatures()->contains($sig1));
        $this->assertFalse($obj->getSignatures()->contains($sig2));
    }

    public function testSetEvents()
    {
        $obj = new Match();
        $collection = $obj->getEvents();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event0->shouldReceive('getTeam')->andReturnNull();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event1->shouldReceive('getTeam')->andReturnNull();

        $newCollection = new ArrayCollection();
        $newCollection->add($event0);
        $newCollection->add($event1);

        // Do the add
        $obj->setEvents($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(2, $obj->getEvents()->count());
    }

    public function testAddEvent()
    {
        $obj = new Match();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setMatch')->never();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event1->shouldReceive('getTeam')->andReturnNull();

        // Add one to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);

        // Do teh add
        $obj->addEvent($event1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(2, $obj->getEvents()->count());
    }

    public function testAddEventWillNotAddTheSameEventObjectTwice()
    {
        $obj = new Match();
        $collection = $obj->getEvents();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('getType')->andReturn('HC');
        $event0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event0->shouldReceive('getTeam')->andReturnNull();

        // Add event0 twice
        $coll = new ArrayCollection();
        $coll->add($event0);
        $coll->add($event0);
        $obj->addEvents($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(1, $obj->getEvents()->count());
    }

    public function testAddEvents()
    {
        $obj = new Match();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('getType')->andReturn('HC');
        $event0->shouldReceive('setMatch')->never();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('getType')->andReturn('AC');
        $event1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event1->shouldReceive('getTeam')->andReturnNull();
        $event2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event2->shouldReceive('getType')->andReturn('REF');
        $event2->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $event2->shouldReceive('getTeam')->andReturnNull();

        // Add one to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($event1);
        $coll->add($event2);
        $obj->addEvents($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(3, $obj->getEvents()->count());
    }

    public function testHasEvent()
    {
        $obj = new Match();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('getType')->andReturn('HC');
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('getType')->andReturn('AC');

        // Add roles to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);

        $this->assertTrue($obj->hasEvent($event0));
        $this->assertFalse($obj->hasEvent($event1));
    }

    public function testRemoveEvent()
    {
        $obj = new Match();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();
        $event0->shouldReceive('getTeam')->andReturnNull();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setMatch')->never();

        // Add to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);
        $collection->add($event1);

        // Do the remove
        $obj->removeEvent($event0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(1, $obj->getEvents()->count());
        $this->assertFalse($obj->getEvents()->contains($event0));
        $this->assertTrue($obj->getEvents()->contains($event1));
    }

    public function testRemoveEvents()
    {
        $obj = new Match();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();
        $event0->shouldReceive('getTeam')->andReturnNull();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setMatch')->never();
        $event2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event2->shouldReceive('setMatch')->withArgs([null])->once()->andReturnSelf();
        $event2->shouldReceive('getTeam')->andReturnNull();

        // Add one to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);
        $collection->add($event1);
        $collection->add($event2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($event0);
        $coll->add($event2);
        $obj->removeEvents($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getEvents());
        $this->assertSame($collection, $obj->getEvents());
        $this->assertEquals(1, $obj->getEvents()->count());
        $this->assertFalse($obj->getEvents()->contains($event0));
        $this->assertTrue($obj->getEvents()->contains($event1));
        $this->assertFalse($obj->getEvents()->contains($event2));
    }
}
