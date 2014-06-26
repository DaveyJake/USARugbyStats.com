<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('zfcuser_doctrine_em');

        $service = new TeamService();
        $service->setObjectManager($em);
        $service->setRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Team'));
        $service->setCreateForm($sm->get('usarugbystats_competition_team_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_team_updateform'));

        return $service;
    }
}
