<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ExtensionFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $fs = new ExtensionFieldset();
        $fs->setObject(new ExtensionEntity());
        $fs->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        $ext = new Extension();
        $ext->setFieldset($fs);
        $ext->setInputFilter(new ExtensionInputFilter());
        $ext->setService($sm->get('usarugbystats-accountprofile_extprofile_extension_service'));

        return $ext;
    }
}
