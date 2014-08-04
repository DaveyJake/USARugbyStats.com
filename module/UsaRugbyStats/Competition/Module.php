<?php
namespace UsaRugbyStats\Competition;

use Zend\Mvc\MvcEvent;
use UsaRugbyStats\Competition\Listeners\AuditLogCommentSetterListener;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $sm = $e->getApplication()->getServiceManager();
        if ( $sm->has('auditService') ) {
            $auditService = $sm->get('auditService');

            $listener = new AuditLogCommentSetterListener($auditService);
            $listener->attach($sm->get('usarugbystats_competition_team_service')->getEventManager());
            $listener->attach($sm->get('usarugbystats_competition_union_service')->getEventManager());
            $listener->attach($sm->get('usarugbystats_competition_competition_service')->getEventManager());
            $listener->attach($sm->get('usarugbystats_competition_competition_match_service')->getEventManager());
        }

        $em = $sm->get('usarugbystats_competition_union_service')->getEventManager();
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_emptyunionteamcollection'));
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
