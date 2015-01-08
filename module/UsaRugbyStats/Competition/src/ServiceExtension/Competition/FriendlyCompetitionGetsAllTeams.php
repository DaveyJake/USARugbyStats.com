<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Competition;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use UsaRugbyStats\Competition\Service\TeamService;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class FriendlyCompetitionGetsAllTeams implements ServiceExtensionInterface
{
    /**
     * @var TeamService
     */
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function checkPrecondition(EventInterface $e)
    {
        // Only applies to friendlies
        return $e->getParams()->entity->isFriendly();
    }

    public function execute(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $entity instanceof Competition;

        $division = $entity->getDivisions()->first();

        // Reset Division Structure
        // Leave just first division, transfer all other teams to it
        if ($entity->getDivisions()->count() > 1) {
            $entity->getDivisions()->map(function ($div) use ($entity, $division) {
                if ($division === $div) {
                    return;
                }
                $tmset = $div->getTeamMemberships();
                $div->removeTeamMemberships($tmset);
                $division->addTeamMemberships($tmset);
                $entity->removeDivision($div);
            });
        }

        // Create a new "Friendly Pool" division if comp has none
        if (! $division instanceof Division) {
            $division = new Division();
            $division->setName('Friendly Pool');
            $entity->addDivision($division);
        }

        // Add every team in the system
        $teams = $this->teamService->getRepository()->findAll();
        foreach ($teams as $team) {
            $division->addTeam($team);
        }
    }
}
