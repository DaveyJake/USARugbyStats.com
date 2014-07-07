<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEventTest;

class CardEventTest extends MatchTeamEventTest
{
    protected $entityClass = 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent';

    public function testGetDiscriminator()
    {
        $obj = new $this->entityClass();
        $this->assertEquals('card', $obj->getDiscriminator());
    }

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
        $obj->setPlayer(null);
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
     * Data Provider for testGetSetType (lists valid Card types)
     *
     * @return array
     */
    public function providerGetSetType()
    {
        return [ ['R',true], ['Y',true], ['X',false], [null,false] ];
    }
}
