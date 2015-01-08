<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Division;
use UsaRugbyStats\Competition\Service\CompetitionService;

class UpdateFriendlyCompetitionsWithTeamChanges implements ServiceExtensionInterface
{
    /**
     * @var CompetitionService
     */
    protected $competitionService;

    public function __construct(CompetitionService $competitionService)
    {
        $this->competitionService = $competitionService;
    }

    public function checkPrecondition(EventInterface $e)
    {
        return ( $e->getParams()->entity instanceof Team );
    }

    public function execute(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $comps = $this->competitionService->findFriendlyCompetitions();
        if (empty($comps)) {
            return;
        }

        foreach ($comps as $comp) {
            if ( $comp->getDivisions()->count() === 0 ) {
                $div = new Division();
                $div->setName('Friendly Pool');
                $comp->addDivision($div);
            }
            if ( ! $comp->hasTeam($entity) ) {
                $comp->getDivisions()->first()->addTeam($entity);
            }
        }
    }
}
