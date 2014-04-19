<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetCompetition()
    {
        $obj = new Match();

        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(NULL);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetHomeTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['H'])->once()->andReturnSelf();

        $obj->setHomeTeam($team0);

        $this->assertSame($team0, $obj->getHomeTeam());
    }

    public function testSetHomeTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setHomeTeam(NULL);
    }

    public function testGetSetAwayTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['A'])->once()->andReturnSelf();

        $obj->setAwayTeam($team0);

        $this->assertSame($team0, $obj->getAwayTeam());
    }

    public function testSetAwayTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setAwayTeam(NULL);
    }

    public function testGetSetDate()
    {
        $obj = new Match();

        $date = new \DateTime();

        $obj->setDate($date);

        $this->assertSame($date, $obj->getDate());
    }

    public function testSetDateDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setDate(NULL);
    }
}
