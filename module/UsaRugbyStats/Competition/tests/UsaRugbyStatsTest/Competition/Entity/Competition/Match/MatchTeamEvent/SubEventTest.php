<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStatsTest\Competition\Entity\Competition\Match\MatchTeamEventTest;

class SubEventTest extends MatchTeamEventTest
{
    protected $entityClass = 'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent';

    public function testGetDiscriminator()
    {
        $obj = new $this->entityClass();
        $this->assertEquals('sub', $obj->getDiscriminator());
    }

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
        $obj->setPlayerOn(null);
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
        $obj->setPlayerOff(null);
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
     * Data Provider for testGetSetType (lists valid Sub types)
     *
     * @return array
     */
    public function providerGetSetType()
    {
        return [ ['BL',true], ['IJ',true], ['FRC',true], ['TC',true], ['X',false], [null,false] ];
    }
}
