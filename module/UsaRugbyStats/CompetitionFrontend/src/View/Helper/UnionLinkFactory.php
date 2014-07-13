<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

class UnionLinkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $sl = ( $pluginManager instanceof AbstractPluginManager )
            ? $pluginManager->getServiceLocator()
            : $pluginManager;

        $viewHelper = new UnionLink();
        $viewHelper->setUnionService($sl->get('usarugbystats_competition_union_service'));

        return $viewHelper;
    }
}
