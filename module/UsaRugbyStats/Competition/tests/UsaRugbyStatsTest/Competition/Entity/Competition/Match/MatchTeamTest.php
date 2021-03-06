<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Doctrine\Common\Collections\ArrayCollection;

class MatchTeamTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new MatchTeam();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetScore()
    {
        $obj = new MatchTeam();
        $this->assertEquals(0, $obj->getScore());
        $obj->setScore(5);
        $this->assertEquals(5, $obj->getScore());
    }

    public function testGetSetScoreAcceptsAStringLiteral()
    {
        $obj = new MatchTeam();
        $obj->setScore('5');
        $this->assertEquals(5, $obj->getScore());
    }

    public function testCanBeConvertedToString()
    {
        $mockTeam = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getName')->andReturn('Foobar');

        $obj = new MatchTeam();
        $obj->setTeam($mockTeam);

        $this->assertTrue(method_exists($obj, '__toString'));
        (string) $obj;
    }

    /**
     * If the entity is to be used in a form collection it's internal Doctrine collections must
     * be reinitialized on clone or else all the clones will share the same instance of each collection
     *
     * @group GH-20
     */
    public function testDoctrineCollectionsAreReplacedWhenObjectIsCloned()
    {
        $obj = new MatchTeam();
        $players = $obj->getPlayers();
        $events = $obj->getEvents();

        $newObj = clone $obj;
        $this->assertNotSame($players, $newObj->getPlayers());
        $this->assertNotSame($events, $newObj->getEvents());
    }

    public function testGetSetMatch()
    {
        $obj = new MatchTeam();

        $match = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');

        // Test setting to an instance of Match class
        $obj->setMatch($match);
        $this->assertSame($match, $obj->getMatch());
    }

    public function testSetMatchAcceptsNull()
    {
        $obj = new MatchTeam();

        // Test setting to null (disassociate from competition)
        $obj->setMatch(null);
        $this->assertNull($obj->getMatch());
    }

    public function testGetSetTeam()
    {
        $obj = new MatchTeam();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        $obj->setTeam($team0);

        $this->assertSame($team0, $obj->getTeam());
    }

    public function testSetTeamDoesNotAcceptNull()
    {
        $obj = new MatchTeam();

        // Test setting to null (disassociate from team)
        $obj->setTeam(null);
        $this->assertNull($obj->getTeam());
    }

    /**
     * @dataProvider providerGetSetType
     */
    public function testGetSetType($type, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new MatchTeam();
        $obj->setType($type);
        $this->assertEquals($type, $obj->getType());
    }

    /**
     * Data Provider for testGetSetType (lists valid MatchTeam types)
     *
     * @return array
     */
    public function providerGetSetType()
    {
        return [ ['H',true], ['A',true], ['X',false], [null,false] ];
    }

    public function testIsMethodsWhenTypeFieldContainsH()
    {
        $obj = new MatchTeam();
        $obj->setType('H');

        $this->assertTrue($obj->isHomeTeam());
        $this->assertFalse($obj->isAwayTeam());
    }

    public function testIsMethodsWhenTypeFieldContainsA()
    {
        $obj = new MatchTeam();
        $obj->setType('A');

        $this->assertFalse($obj->isHomeTeam());
        $this->assertTrue($obj->isAwayTeam());
    }

    public function testSetPlayers()
    {
        $obj = new MatchTeam();
        $collection = $obj->getPlayers();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player0->shouldReceive('getPosition')->andReturn('P1');
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player1->shouldReceive('getPosition')->andReturn('P2');

        $newCollection = new ArrayCollection();
        $newCollection->add($player0);
        $newCollection->add($player1);

        // Do the add
        $obj->setPlayers($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(2, $obj->getPlayers()->count());
    }

    public function testAddPlayer()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->never();
        $player0->shouldReceive('getPosition')->andReturn('P1');
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player1->shouldReceive('getPosition')->andReturn('P2');

        // Add one to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);

        // Do teh add
        $obj->addPlayer($player1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(2, $obj->getPlayers()->count());
    }

    public function testAddPlayerDoesNotAllowDuplicates()
    {
        $obj = new MatchTeam();
        $collection = $obj->getPlayers();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player0->shouldReceive('getPosition')->andReturn('P2');

        // Add player0 twice
        $coll = new ArrayCollection();
        $coll->add($player0);
        $coll->add($player0);
        $obj->addPlayers($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(1, $obj->getPlayers()->count());
    }

    public function testAddPlayers()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->never();
        $player0->shouldReceive('getPosition')->andReturn('P1');
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player1->shouldReceive('getPosition')->andReturn('P2');
        $player2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player2->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player2->shouldReceive('getPosition')->andReturn('H');

        // Add one to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($player1);
        $coll->add($player2);
        $obj->addPlayers($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(3, $obj->getPlayers()->count());
    }

    public function testHasPlayer()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('getPosition')->andReturn('P1');
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('getPosition')->andReturn('P2');

        // Add roles to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);

        $this->assertTrue($obj->hasPlayer($player0));
        $this->assertFalse($obj->hasPlayer($player1));
    }

    public function testHasPlayerWhenPassedAnAccountInstance()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('getPlayer->getId')->andReturn(42);

        $account0 = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');
        $account0->shouldReceive('getId')->andReturn(42);
        $account1 = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');
        $account1->shouldReceive('getId')->andReturn(99);

        // Add roles to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);

        $this->assertTrue($obj->hasPlayer($account0));
        $this->assertFalse($obj->hasPlayer($account1));
    }

    public function testRemovePlayer()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->never();

        // Add to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);
        $collection->add($player1);

        // Do the remove
        $obj->removePlayer($player0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(1, $obj->getPlayers()->count());
        $this->assertFalse($obj->getPlayers()->contains($player0));
        $this->assertTrue($obj->getPlayers()->contains($player1));
    }

    public function testRemovePlayers()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->never();
        $player2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player2->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);
        $collection->add($player1);
        $collection->add($player2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($player0);
        $coll->add($player2);
        $obj->removePlayers($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getPlayers());
        $this->assertSame($collection, $obj->getPlayers());
        $this->assertEquals(1, $obj->getPlayers()->count());
        $this->assertFalse($obj->getPlayers()->contains($player0));
        $this->assertTrue($obj->getPlayers()->contains($player1));
        $this->assertFalse($obj->getPlayers()->contains($player2));
    }

    public function testSetEvents()
    {
        $obj = new MatchTeam();
        $collection = $obj->getEvents();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $event0->shouldReceive('setMatch')->once()->andReturnSelf();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $event1->shouldReceive('setMatch')->once()->andReturnSelf();

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
        $obj = new MatchTeam();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setTeam')->never();
        $event0->shouldReceive('setMatch')->never();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $event1->shouldReceive('setMatch')->once()->andReturnSelf();

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
        $obj = new MatchTeam();
        $collection = $obj->getEvents();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $event0->shouldReceive('setMatch')->once()->andReturnSelf();

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
        $obj = new MatchTeam();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setMatch')->never();
        $event0->shouldReceive('setTeam')->never();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setMatch')->once()->andReturnSelf();
        $event1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $event2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event2->shouldReceive('setMatch')->once()->andReturnSelf();
        $event2->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();

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
        $obj = new MatchTeam();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');

        // Add roles to the existing collection
        $collection = $obj->getEvents();
        $collection->add($event0);

        $this->assertTrue($obj->hasEvent($event0));
        $this->assertFalse($obj->hasEvent($event1));
    }

    public function testRemoveEvent()
    {
        $obj = new MatchTeam();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();
        $event0->shouldReceive('setMatch')->once()->andReturnSelf();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setTeam')->never();
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
        $obj = new MatchTeam();

        $event0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event0->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();
        $event0->shouldReceive('setMatch')->once()->andReturnSelf();
        $event1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event1->shouldReceive('setTeam')->never();
        $event1->shouldReceive('setMatch')->never();
        $event2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent');
        $event2->shouldReceive('setTeam')->withArgs([null])->once()->andReturnSelf();
        $event2->shouldReceive('setMatch')->once()->andReturnSelf();

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
