<?php
namespace UsaRugbyStats\Account\ZfcUser;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class UserMapperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserMapper(
            $sm->get('zfcuser_doctrine_em'),
            $sm->get('zfcuser_module_options')
        );
    }
}