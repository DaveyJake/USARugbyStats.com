<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition\Match;

use Mockery;

abstract class MatchTeamEventTest extends \PHPUnit_Framework_TestCase
{
    protected $entityClass;

    public function testGetSetId()
    {
        $obj = new $this->entityClass();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetMinute()
    {
        $obj = new $this->entityClass();
        $this->assertNull($obj->getMinute());
        $obj->setMinute(29);
        $this->assertEquals(29, $obj->getMinute());
    }

    public function testGetSetMinuteAcceptsStringLiteral()
    {
        $obj = new $this->entityClass();
        $this->assertNull($obj->getMinute());
        $obj->setMinute('29');
        $this->assertEquals(29, $obj->getMinute());
    }

    public function testGetSetRunningScoreReturnsZerosByDefault()
    {
        $obj = new $this->entityClass();
        $this->assertEquals(['H' => 0, 'A' => 0], $obj->getRunningScore());
    }

    public function testGetSetRunningScoreAcceptsValidScores()
    {
        $obj = new $this->entityClass();
        $obj->setRunningScore(['H' => 5, 'A' => 0]);
        $this->assertEquals(['H' => 5, 'A' => 0], $obj->getRunningScore());
    }

    public function testGetSetRunningScoreIgnoresInvalidScores()
    {
        $obj = new $this->entityClass();
        $obj->setRunningScore(['H' => 5, 'A' => 5]);
        $this->assertEquals(['H' => 5, 'A' => 5], $obj->getRunningScore());
        $obj->setRunningScore(['H' => 4, 'A' => 'HH']);
        $this->assertEquals(['H' => 4, 'A' => 5], $obj->getRunningScore());
        $obj->setRunningScore(['H' => 'ZZ', 'A' => 4]);
        $this->assertEquals(['H' => 4, 'A' => 4], $obj->getRunningScore());
        $obj->setRunningScore(['H' => 'ZZ', 'A' => 'HH']);
        $this->assertEquals(['H' => 4, 'A' => 4], $obj->getRunningScore());
    }

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
        $obj->setTeam(null);
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
        $obj->setMatch(null);
        $this->assertNull($obj->getMatch());
    }
}
