<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtensionServiceFactory implements FactoryInterface
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

        $service = new ExtensionService();
        $service->setObjectManager($em);
        $service->setRepository($em->getRepository('UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionEntity'));

        return $service;
    }
}
