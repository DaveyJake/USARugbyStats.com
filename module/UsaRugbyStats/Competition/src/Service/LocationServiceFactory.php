<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationServiceFactory implements FactoryInterface
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

        $service = new LocationService();
        $service->setObjectManager($em);
        $service->setRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Location'));
        $service->setCreateForm($sm->get('usarugbystats_competition_location_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_location_updateform'));

        return $service;
    }
}
