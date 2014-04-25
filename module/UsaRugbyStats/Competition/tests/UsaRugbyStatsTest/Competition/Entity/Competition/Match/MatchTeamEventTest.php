<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match;

use Mockery;

abstract class MatchTeamEventTest extends \PHPUnit_Framework_TestCase
{
    protected $entityClass;

    public function testGetSetTeam()
    {
        $obj = new $this->entityClass();

        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');

        // Test setting to an instance of MatchTeam class
        $obj->setTeam($team);
        $this->assertSame($team, $obj->getTeam());
    }

    public function testSetTeamAcceptsNull()
    {
        $obj = new $this->entityClass();

        // Test setting to null (disassociate from MatchTeam)
        $obj->setTeam(NULL);
        $this->assertNull($obj->getTeam());
    }

    public function testGetSetMatch()
    {
        $obj = new $this->entityClass();

        $match = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');

        // Test setting to an instance of MatchTeam class
        $obj->setMatch($match);
        $this->assertSame($match, $obj->getMatch());
    }

    public function testSetMatchAcceptsNull()
    {
        $obj = new $this->entityClass();

        // Test setting to null (disassociate from Match)
        $obj->setMatch(NULL);
        $this->assertNull($obj->getMatch());
    }
}
