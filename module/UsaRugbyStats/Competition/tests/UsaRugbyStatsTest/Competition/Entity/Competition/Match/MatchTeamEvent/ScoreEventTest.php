<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEventTest;

class ScoreEventTest extends MatchTeamEventTest
{
    protected $entityClass = 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent';

    public function testGetSetPlayer()
    {
        $obj = new $this->entityClass();

        $player = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        $obj->setPlayer($player);
        $this->assertSame($player, $obj->getPlayer());
    }

    public function testSetPlayerDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $obj = new $this->entityClass();
        $obj->setPlayer(NULL);
    }

}
