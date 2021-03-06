<?php
namespace UsaRugbyStats\Application;

use Zend\Mvc\MvcEvent;
use Doctrine\DBAL\Types\Type;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager   = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        $eventManager->attach($serviceManager->get('ZfcRbac\View\Strategy\RedirectStrategy'));
        $eventManager->attach($serviceManager->get('ZfcRbac\View\Strategy\UnauthorizedStrategy'));

        Type::overrideType('datetime', 'UsaRugbyStats\Application\Common\Doctrine\UTCDateTimeType');
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
}
