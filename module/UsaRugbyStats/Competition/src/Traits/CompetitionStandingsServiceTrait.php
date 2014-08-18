<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\Competition\StandingsService;

trait CompetitionStandingsServiceTrait
{
    /**
     * @var StandingsService
     */
    protected $competitionStandingsService;

    public function getCompetitionStandingsService()
    {
        if ( is_null($this->competitionStandingsService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->competitionStandingsService = $this->getServiceLocator()->get(
                'usarugbystats_competition_competition_standings_service'
            );
        }

        return $this->competitionStandingsService;
    }

    public function setCompetitionStandingsService(StandingsService $svc)
    {
        $this->competitionStandingsService = $svc;

        return $this;
    }
}
