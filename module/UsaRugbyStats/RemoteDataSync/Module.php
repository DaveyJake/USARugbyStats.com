<?php
namespace UsaRugbyStats\RemoteDataSync;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'usa-rugby-stats_remote-data-sync_provider' => function ($sm) {
                    $config = $sm->get('Config');

                    return $sm->get($config['usa-rugby-stats']['remote-data-sync']['provider']);
                },
            ],
        ];
    }

    public function getConsoleBanner(Console $console)
    {
        return __NAMESPACE__;
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'remote-data-sync sync_team --team-id= --wait=' => 'Trigger sync_team job on the specified team',
        );
    }

}
