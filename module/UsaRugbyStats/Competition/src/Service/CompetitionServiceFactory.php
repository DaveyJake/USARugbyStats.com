<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Application\Service\ServiceExtensionManager;
use UsaRugbyStats\Application\Service\ServiceExtensionManagerConfig;

class CompetitionServiceFactory implements FactoryInterface
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

        $service = new CompetitionService();
        $service->setObjectManager($em);
        $service->setRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition'));
        $service->setCreateForm($sm->get('usarugbystats_competition_competition_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_competition_updateform'));

        //@TODO this should probably be in an initializer or abstract factory
        $config = @$sm->get('Config')['usarugbystats']['service_extensions']['usarugbystats_competition_competition_service'];
        if ( is_array($config) ) {
            $extmgr = new ServiceExtensionManager(new ServiceExtensionManagerConfig($config));
            $extmgr->addPeeringServiceManager($sm);
            $service->setExtensionManager($extmgr);
        }

        return $service;
    }
}
