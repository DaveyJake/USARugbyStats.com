<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEventTest;

class ScoreEventTest extends MatchTeamEventTest
{
    protected $entityClass = 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent';

    public function testGetDiscriminator()
    {
        $obj = new $this->entityClass();
        $this->assertEquals('score', $obj->getDiscriminator());
    }

    public function testGetSetPlayer()
    {
        $obj = new $this->entityClass();

        $player = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        $obj->setPlayer($player);
        $this->assertSame($player, $obj->getPlayer());
    }

    public function testSetPlayerDoesAcceptNull()
    {
        $obj = new $this->entityClass();
        $obj->setPlayer(null);
        $this->assertNull($obj->getPlayer());
    }

    /**
     * @dataProvider providerGetSetType
     */
    public function testGetSetType($type, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new $this->entityClass();
        $obj->setType($type);
        $this->assertEquals($type, $obj->getType());
    }

    /**
     * Data Provider for testGetSetType (lists valid Score types)
     *
     * @return array
     */
    public function providerGetSetType()
    {
        return [ ['CV',true], ['DG',true], ['PK',true], ['PT',true], ['TR',true], ['X',false], [null,false] ];
    }
}
