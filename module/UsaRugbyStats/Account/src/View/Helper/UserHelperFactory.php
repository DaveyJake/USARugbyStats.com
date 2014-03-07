<?php
namespace UsaRugbyStats\Account\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $zfcUserIdentity = $sm->get('zfcUserIdentity');
        $config = $sm->getServiceLocator()->get('Config');
        
        $obj = new UserHelper();
        $obj->setAuthService($zfcUserIdentity->getAuthService());
        $obj->setEntityClass($config['zfcuser']['user_entity_class']);
        return $obj;
    }
}