<?php
namespace UsaRugbyStatsTest\Competition\View\Helper;

use Mockery;
use UsaRugbyStats\Competition\View\Helper\TeamAbbreviation;

class TeamAbbreviationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->helper = new TeamAbbreviation();
    }

    public function testInvokeWhenSubjectIsATeamObject()
    {
        $mock = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mock->shouldReceive('getAbbreviation')->andReturn('test');

        $this->assertEquals('test', $this->helper->__invoke($mock));
    }

    public function testInvokeWhenSubjectIsAMatchTeamObject()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getAbbreviation')->andReturn('test');
        $mockMatchTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $mockMatchTeam->shouldReceive('getTeam')->andReturn($mockTeam);

        $this->assertEquals('test', $this->helper->__invoke($mockMatchTeam));
    }

    public function testInvokeReturnsNullWhenSubjectIsAMatchTeamObjectButDoesNotContainATeam()
    {
        $mockMatchTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $mockMatchTeam->shouldReceive('getTeam')->andReturn(null);

        $this->assertNull($this->helper->__invoke($mockMatchTeam));
    }

    public function testInvokeAttemptsToLoadTeamFromRepositoryWhenGivenAScalar()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getAbbreviation')->andReturn('test');

        $repo = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $repo->shouldReceive('findByID')->with(9999)->andReturn($mockTeam);
        $this->helper->setTeamService($repo);

        $this->assertEquals('test', $this->helper->__invoke(9999));
    }

    public function testInvokeReturnsNullIfProvidedObjectIsNotATeam()
    {
        $this->assertNull($this->helper->__invoke(new \stdClass()));
    }

    public function testInvokeShouldReturnFullNameWhenAbbreviationIsEmptyAndFlagIsToggled()
    {
        $mock = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mock->shouldReceive('getAbbreviation')->andReturnNull();
        $mock->shouldReceive('getName')->andReturn('Full Team Name');

        $this->assertEquals('Full Team Name', $this->helper->__invoke($mock, true));
    }
}
