<?php
namespace UsaRugbyStats\DataImporter;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{

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

    public function getConsoleBanner(Console $console)
    {
        return __NAMESPACE__;
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'data-importer run-task --task= [--file=]' => 'Run data import task',
            'data-importer run-fixtures --group=' => 'Run pre-defined fixtures from group',
        );
    }

}
