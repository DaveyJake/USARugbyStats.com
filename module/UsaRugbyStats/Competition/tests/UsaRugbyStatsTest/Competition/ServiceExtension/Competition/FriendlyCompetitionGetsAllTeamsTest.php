<?php
namespace UsaRugbyStatsTest\Competition\ServiceExtension\Competition;

use UsaRugbyStats\Competition\ServiceExtension\Competition\FriendlyCompetitionGetsAllTeams;
use Zend\EventManager\Event;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;
use UsaRugbyStats\Competition\Entity\Competition\Division;
use UsaRugbyStats\Competition\Entity\Competition;

class FriendlyCompetitionGetsAllTeamsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->teamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');

        $this->extension = new FriendlyCompetitionGetsAllTeams($this->teamService);
    }

    public function testPreconditionAcceptsFriendlyCompetitions()
    {
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('isFriendly')->andReturn(true)->once();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->assertTrue($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsCompetitionThatIsNotAFriendly()
    {
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('isFriendly')->andReturn(false)->once();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testExecuteWithNoTeamsOrDivisions()
    {
        $tmset = new ArrayCollection();
        $divset = new ArrayCollection();

        // All existing team memberships and divisions are removed
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('getDivisions')->andReturn($divset);
        $competition->shouldReceive('removeDivisions')->never();

        // All existing teams are added to a division called "Friendly Pool"
        $teamset = new ArrayCollection([
            (new Team())->setId(123)->setName('Test 1'),
            (new Team())->setId(456)->setName('Test 2'),
        ]);
        $this->teamService->shouldReceive('getRepository->findAll')->andReturn($teamset)->once();
        $competition->shouldReceive('addDivision')->once()->andReturnUsing(function ($div) {
            $this->assertEquals('Friendly Pool', $div->getName());
        });

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);
    }

    public function testExecuteWithExistingTeamsAndDivisions()
    {
        $div1 = new Division();
        $div1->setName('D1');
        $div2 = new Division();
        $div2->setName('D2');

        $team1 = (new Team())->setId(42)->setName('T1');
        $team2 = (new Team())->setId(24)->setName('T2');
        $team3 = (new Team())->setId(123)->setName('T3');
        $team4 = (new Team())->setId(456)->setName('T4');

        $tm1 = new TeamMembership();
        $tm1->setTeam($team1);
        $div1->addTeamMembership($tm1);
        $tm2 = new TeamMembership();
        $tm2->setTeam($team2);
        $div2->addTeamMembership($tm2);

        $competition = new Competition();
        $competition->addDivision($div1);
        $competition->addDivision($div2);

        $teamset = new ArrayCollection([$team1, $team2, $team3, $team4]);
        $this->teamService->shouldReceive('getRepository->findAll')->andReturn($teamset)->once();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);

        $division = $competition->getDivisions()->first();
        $this->assertSame($div1, $division);
        $this->assertFalse($competition->hasDivision($div2));

        $this->assertTrue($division->hasTeam($team1));
        $this->assertTrue($division->hasTeam($team2));
        $this->assertTrue($division->hasTeam($team3));
        $this->assertTrue($division->hasTeam($team4));
    }
}
