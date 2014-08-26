<?php
namespace UsaRugbyStats\CompetitionFrontendEmbed;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function(MvcEvent $e) {
            if ( ! preg_match('{^usarugbystats_frontend-embed_}is', $e->getRouteMatch()->getMatchedRouteName()) ) {
                return;
            }
            $e->getTarget()->layout('layout/embedded');
        }, 100);
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
