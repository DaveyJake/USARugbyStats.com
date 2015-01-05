<?php
namespace UsaRugbyStatsTest\Competition\ServiceExtension\Team;

use UsaRugbyStats\Competition\ServiceExtension\Team\UpdateFriendlyCompetitionsWithTeamChanges;
use Zend\EventManager\Event;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition;

class UpdateFriendlyCompetitionsWithTeamChangesTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competitionService = \Mockery::mock('UsaRugbyStats\Competition\Service\CompetitionService');

        $this->extension = new UpdateFriendlyCompetitionsWithTeamChanges($this->competitionService);
    }

    public function testPreconditionAcceptsValidTeamObject()
    {
        $team = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        $params = new \stdClass();
        $params->entity = $team;
        $event = new Event(null, null, $params);

        $this->assertTrue($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsInvalidTeamObject()
    {
        $params = new \stdClass();
        $params->entity = new \stdClass();
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testExecuteWhenThereAreNoFriendlyCompetitions()
    {
        $team = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $this->competitionService->shouldReceive('findFriendlyCompetitions')->andReturn(new ArrayCollection());

        $params = new \stdClass();
        $params->entity = $team;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);
    }

    public function testExecuteWhenThereAreFriendlyCompetitions()
    {
        $comp1 = new Competition();
        $comp2 = new Competition();
        $team = new Team();

        // Team is added to first existing Division
        $firstDiv = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $firstDiv->shouldReceive('setCompetition')->with($comp2)->andReturnSelf();
        $firstDiv->shouldReceive('addTeam')->with($team)->andReturnSelf();
        $secondDiv = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $secondDiv->shouldReceive('setCompetition')->with($comp2)->andReturnSelf();
        $secondDiv->shouldReceive('addTeam')->never();

        $comp2->addDivision($firstDiv);
        $comp2->addDivision($secondDiv);

        $coll = new ArrayCollection([$comp1, $comp2]);
        $this->competitionService->shouldReceive('findFriendlyCompetitions')->andReturn($coll);

        $params = new \stdClass();
        $params->entity = $team;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);

        $this->assertCount(1, $comp1->getDivisions());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Division', $comp1->getDivisions()->first());
        $this->assertEquals('Friendly Pool', $comp1->getDivisions()->first()->getName());
        $this->assertCount(1, $comp1->getDivisions()->first()->getTeamMemberships());
        $this->assertSame($team, $comp1->getDivisions()->first()->getTeamMemberships()->first()->getTeam());
    }
}
