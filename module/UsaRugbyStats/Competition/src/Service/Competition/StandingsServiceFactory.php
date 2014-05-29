<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StandingsServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new StandingsService();
        $service->setCompetitionService($sm->get('usarugbystats_competition_competition_service'));
        $service->setMatchService($sm->get('usarugbystats_competition_competition_match_service'));

        return $service;
    }
}
