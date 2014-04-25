<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEventTest;

class SubEventTest extends MatchTeamEventTest
{
    protected $entityClass = 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent';

    public function testGetSetPlayerOn()
    {
        $obj = new $this->entityClass();

        $player = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        $obj->setPlayerOn($player);
        $this->assertSame($player, $obj->getPlayerOn());
    }

    public function testSetPlayerOnDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $obj = new $this->entityClass();
        $obj->setPlayerOn(NULL);
    }

    public function testGetSetPlayerOff()
    {
        $obj = new $this->entityClass();

        $player = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        $obj->setPlayerOff($player);
        $this->assertSame($player, $obj->getPlayerOff());
    }

    public function testSetPlayerOffDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $obj = new $this->entityClass();
        $obj->setPlayerOff(NULL);
    }
}
