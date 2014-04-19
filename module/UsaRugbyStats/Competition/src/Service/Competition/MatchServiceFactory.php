<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchServiceFactory implements FactoryInterface
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

        $service = new MatchService();
        $service->setMatchObjectManager($em);
        $service->setMatchRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match'));
        $service->setCreateForm($sm->get('usarugbystats_competition_competition_match_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_competition_match_updateform'));

        return $service;
    }
}
