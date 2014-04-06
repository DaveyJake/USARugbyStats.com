<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UnionServiceFactory implements FactoryInterface
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
        
        $service = new UnionService();
        $service->setUnionObjectManager($em);
        $service->setUnionRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Union'));
        $service->setCreateForm($sm->get('usarugbystats_competition_union_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_union_updateform'));
        return $service;
    }
}