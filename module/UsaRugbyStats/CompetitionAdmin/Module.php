<?php
namespace UsaRugbyStats\CompetitionAdmin;

use Zend\Mvc\MvcEvent;
use UsaRugbyStats\Competition\Listeners\AuditLogCommentSetterListener;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        if ( $sm->has('auditService') ) {
            $auditService = $sm->get('auditService');

            $emTeamAdminService = $sm->get('usarugbystats_competition-admin_team_service')->getEventManager();

            $listener = new AuditLogCommentSetterListener($auditService);
            $listener->attach($emTeamAdminService);

            $emTeamAdminService->attach('save.post', function () use ($auditService) {
                $auditService->setComment('COMPETITION_TEAM_UPDATE_ADMINS');
            }, 2);
        }
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
