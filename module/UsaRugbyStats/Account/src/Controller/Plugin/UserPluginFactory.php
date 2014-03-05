<?php
namespace UsaRugbyStats\Account\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $zfcUserAuthentication = $sm->get('zfcUserAuthentication');
        
        $obj = new UserPlugin();
        $obj->setAuthService($zfcUserAuthentication->getAuthService());
        $obj->setAuthAdapter($zfcUserAuthentication->getAuthAdapter());
        return $obj;
    }
}