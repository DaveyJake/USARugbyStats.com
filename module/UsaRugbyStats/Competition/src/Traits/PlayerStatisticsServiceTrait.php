<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\PlayerStatisticsService;

trait PlayerStatisticsServiceTrait
{
    /**
     * @var PlayerStatisticsService
     */
    protected $playerStatisticsService;

    public function getPlayerStatisticsService()
    {
        if ( is_null($this->playerStatisticsService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->playerStatisticsService = $this->getServiceLocator()->get(
	            'usarugbystats_competition_playerStatistics_service'
            );
        }
        return $this->playerStatisticsService;
    }

    public function setPlayerStatisticsService(PlayerStatisticsService $svc)
    {
        $this->playerStatisticsService = $svc;
        return $this;
    }
}