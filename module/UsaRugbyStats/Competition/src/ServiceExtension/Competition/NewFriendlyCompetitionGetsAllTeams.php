<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Competition;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use UsaRugbyStats\Competition\Service\TeamService;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class NewFriendlyCompetitionGetsAllTeams implements ServiceExtensionInterface
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
        if ( ! $e->getParams()->entity->isFriendly() ) {
            return false;
        }

        // Only run this for newly-created competitions
        if ( ! empty($e->getParams()->entity->getId()) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $entity instanceof Competition;

        // Clear any pre-selected team memberships
        $entity->removeTeamMemberships($entity->getTeamMemberships());

        // Reset Division Structure
        $entity->removeDivisions($entity->getDivisions());

        // Create a new "Friendly Pool" division
        $division = new Division();
        $division->setName('Friendly Pool');

        // Add every team in the system
        $teams = $this->teamService->getRepository()->findAll();
        foreach ($teams as $team) {
            $division->addTeam($team);
        }

        // Add new division to the competition
        $entity->addDivision($division);
    }
}
