<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompetitionServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('zfcuser_doctrine_em');
        
        $service = new CompetitionService();
        $service->setCompetitionObjectManager($em);
        $service->setCompetitionRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition'));
        $service->setCreateForm($sm->get('usarugbystats_competition_competition_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_competition_updateform'));
        return $service;
    }
}