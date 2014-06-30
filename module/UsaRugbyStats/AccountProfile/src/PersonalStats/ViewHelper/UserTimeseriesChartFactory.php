<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats\ViewHelper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

class UserTimeseriesChartFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $vhm)
    {
        $sm = $vhm instanceof HelperPluginManager
            ? $vhm->getServiceLocator()
            : $vhm;

        $service = new UserTimeseriesChart(
            $sm->get('usarugbystats-accountprofile_personalstats_extension_service')
        );

        return $service;
    }
}
