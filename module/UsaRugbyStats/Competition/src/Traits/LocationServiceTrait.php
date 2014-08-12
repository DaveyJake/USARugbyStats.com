<?php
namespace UsaRugbyStats\Competition\Traits;

use UsaRugbyStats\Competition\Service\LocationService;

trait LocationServiceTrait
{
    /**
     * @var LocationService
     */
    protected $locationService;

    public function getLocationService()
    {
        if ( is_null($this->locationService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->locationService = $this->getServiceLocator()->get(
                'usarugbystats_competition_location_service'
            );
        }

        return $this->locationService;
    }

    public function setLocationService(LocationService $svc)
    {
        $this->locationService = $svc;

        return $this;
    }
}
