<?php
namespace UsaRugbyStats\Account\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $zfcUserAuthentication = $sm->get('zfcUserAuthentication');
        $config = $sm->getServiceLocator()->get('Config');

        $obj = new UserPlugin();
        $obj->setAuthService($zfcUserAuthentication->getAuthService());
        $obj->setAuthAdapter($zfcUserAuthentication->getAuthAdapter());
        $obj->setEntityClass($config['zfcuser']['user_entity_class']);

        return $obj;
    }
}
