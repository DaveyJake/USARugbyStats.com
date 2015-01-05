<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewFriendlyCompetitionGetsAllTeamsFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new NewFriendlyCompetitionGetsAllTeams(
            $sm->get('usarugbystats_competition_team_service')
        );

        return $obj;
    }
}
