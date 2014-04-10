<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetCompetition()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
    
        $obj = new Game();
    
        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());
    
        // Test setting to null (disassociate from competition)
        $obj->setCompetition(NULL);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetHomeTeam()
    {
        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        $obj = new Game();
    
        // Test setting to an instance of Team class
        $obj->setHomeTeam($team);
        $this->assertSame($team, $obj->getHomeTeam());
    }    

    public function testSetHomeTeamDoesNotAllowNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $obj = new Game();
        $obj->setHomeTeam(NULL);
    }

    public function testGetSetAwayTeam()
    {
        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
    
        $obj = new Game();
    
        // Test setting to an instance of Team class
        $obj->setAwayTeam($team);
        $this->assertSame($team, $obj->getAwayTeam());
    }
    
    public function testSetAwayTeamDoesNotAllowNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $obj = new Game();
        $obj->setAwayTeam(NULL);
    }
}