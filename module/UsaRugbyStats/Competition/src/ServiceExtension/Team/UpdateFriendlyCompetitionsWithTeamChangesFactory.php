<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UpdateFriendlyCompetitionsWithTeamChangesFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new UpdateFriendlyCompetitionsWithTeamChanges(
            $sm->get('usarugbystats_competition_competition_service')
        );

        return $obj;
    }
}
