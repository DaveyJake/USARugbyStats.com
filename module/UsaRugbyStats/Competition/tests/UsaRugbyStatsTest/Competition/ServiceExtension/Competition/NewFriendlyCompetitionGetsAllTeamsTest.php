<?php
namespace UsaRugbyStatsTest\Competition\ServiceExtension\Competition;

use UsaRugbyStats\Competition\ServiceExtension\Competition\NewFriendlyCompetitionGetsAllTeams;
use Zend\EventManager\Event;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class NewFriendlyCompetitionGetsAllTeamsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->teamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');

        $this->extension = new NewFriendlyCompetitionGetsAllTeams($this->teamService);
    }

    public function testPreconditionAcceptsNewFriendlyCompetitions()
    {
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('isFriendly')->andReturn(true)->once();
        $competition->shouldReceive('getId')->andReturn(null)->once();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->assertTrue($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsNewCompetitionThatIsNotAFriendly()
    {
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('isFriendly')->andReturn(false)->once();
        $competition->shouldReceive('getId')->never();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsExistingCompetition()
    {
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('isFriendly')->andReturn(true)->once();
        $competition->shouldReceive('getId')->andReturn(9999)->once();

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testExecute()
    {
        $tmset = new ArrayCollection();
        $divset = new ArrayCollection();

        // All existing team memberships and divisions are removed
        $competition = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');
        $competition->shouldReceive('getTeamMemberships')->andReturn($tmset);
        $competition->shouldReceive('removeTeamMemberships')->with($tmset)->once();
        $competition->shouldReceive('getDivisions')->andReturn($divset);
        $competition->shouldReceive('removeDivisions')->with($divset)->once();

        // All existing teams are added to a division called "Friendly Pool"
        $teamset = new ArrayCollection([
            (new Team())->setId(123)->setName('Test 1'),
            (new Team())->setId(456)->setName('Test 2'),
        ]);
        $this->teamService->shouldReceive('getRepository->findAll')->andReturn($teamset)->once();
        $competition->shouldReceive('addDivision')->once()->andReturnUsing(function ($div) {
            $this->assertEquals('Friendly Pool', $div->getName());
            $this->assertCount(2, $div->getTeamMemberships());
        });

        $params = new \stdClass();
        $params->entity = $competition;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);
    }
}
