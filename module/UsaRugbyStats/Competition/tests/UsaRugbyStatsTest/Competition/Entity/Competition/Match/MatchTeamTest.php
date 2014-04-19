<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Doctrine\Common\Collections\ArrayCollection;

class MatchTeamTest extends \PHPUnit_Framework_TestCase
{

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
        $obj->setMatch(NULL);
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
        $obj->setTeam(NULL);
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
        return [ ['H',true], ['A',true], ['X',false], [NULL,false] ];
    }

    public function testSetPlayers()
    {
        $obj = new MatchTeam();
        $collection = $obj->getPlayers();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();

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
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();

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
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();
        $player2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player2->shouldReceive('setTeam')->withArgs([$obj])->once()->andReturnSelf();

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
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        // Add roles to the existing collection
        $collection = $obj->getPlayers();
        $collection->add($player0);

        $this->assertTrue($obj->hasPlayer($player0));
        $this->assertFalse($obj->hasPlayer($player1));
    }

    public function testRemovePlayer()
    {
        $obj = new MatchTeam();

        $player0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player0->shouldReceive('setTeam')->withArgs([NULL])->once()->andReturnSelf();
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
        $player0->shouldReceive('setTeam')->withArgs([NULL])->once()->andReturnSelf();
        $player1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player1->shouldReceive('setTeam')->never();
        $player2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $player2->shouldReceive('setTeam')->withArgs([NULL])->once()->andReturnSelf();

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

}
