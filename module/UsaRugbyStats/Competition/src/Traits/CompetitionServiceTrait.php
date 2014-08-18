<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\CompetitionService;

trait CompetitionServiceTrait
{
    /**
     * @var CompetitionService
     */
    protected $competitionService;

    public function getCompetitionService()
    {
        if ( is_null($this->competitionService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->competitionService = $this->getServiceLocator()->get(
                'usarugbystats_competition_competition_service'
            );
        }

        return $this->competitionService;
    }

    public function setCompetitionService(CompetitionService $svc)
    {
        $this->competitionService = $svc;

        return $this;
    }
}
