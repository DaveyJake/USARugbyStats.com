<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\Competition\MatchService;

trait CompetitionMatchServiceTrait
{
    /**
     *
     * @var MatchService
     */
    protected $competitionMatchService;

    public function getCompetitionMatchService()
    {
        if ( is_null($this->competitionMatchService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->competitionMatchService = $this->getServiceLocator()->get(
                'usarugbystats_competition_competition_match_service'
            );
        }

        return $this->competitionMatchService;
    }

    public function setCompetitionMatchService(MatchService $svc)
    {
        $this->competitionMatchService = $svc;

        return $this;
    }
}
