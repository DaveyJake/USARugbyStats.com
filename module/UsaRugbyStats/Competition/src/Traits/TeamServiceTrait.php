<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\TeamService;

trait TeamServiceTrait
{
    /**
     * @var TeamService
     */
    protected $teamService;

    public function getTeamService()
    {
        if ( is_null($this->teamService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->teamService = $this->getServiceLocator()->get(
                'usarugbystats_competition_team_service'
            );
        }

        return $this->teamService;
    }

    public function setTeamService(TeamService $svc)
    {
        $this->teamService = $svc;

        return $this;
    }
}
