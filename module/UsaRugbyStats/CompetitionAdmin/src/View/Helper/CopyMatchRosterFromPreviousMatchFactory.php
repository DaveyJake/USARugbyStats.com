<?php
namespace UsaRugbyStats\CompetitionAdmin\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

class CopyMatchRosterFromPreviousMatchFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return SyncTeam
     */
    public function createService(ServiceLocatorInterface $pm)
    {
        $sm = $pm instanceof HelperPluginManager
            ? $pm->getServiceLocator()
            : $pm;

        $injector = new CopyMatchRosterFromPreviousMatch();

        return $injector;
    }
}
