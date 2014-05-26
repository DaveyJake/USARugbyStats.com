<?php
namespace UsaRugbyStats\Competition;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $sm = $e->getApplication()->getServiceManager();

        $auditService = $sm->get('auditService');
        $auditService instanceof \SoliantEntityAudit\Service\AuditService;

        $em = $sm->get('usarugbystats_competition_team_service')->getEventManager();
        $em->attach('create', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_TEAM_CREATE'); });
        $em->attach('update', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_TEAM_UPDATE'); });
        $em->attach('remove', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_TEAM_DELETE'); });

        $em = $sm->get('usarugbystats_competition_union_service')->getEventManager();
        $em->attach('create', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_UNION_CREATE'); });
        $em->attach('update', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_UNION_UPDATE'); });
        $em->attach('remove', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_UNION_DELETE'); });

        $em = $sm->get('usarugbystats_competition_competition_service')->getEventManager();
        $em->attach('create', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMP_CREATE'); });
        $em->attach('update', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMP_UPDATE'); });
        $em->attach('remove', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMP_DELETE'); });

        $em = $sm->get('usarugbystats_competition_competition_match_service')->getEventManager();
        $em->attach('create', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_CREATE'); });
        $em->attach('update', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_UPDATE'); });
        $em->attach('remove', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_DELETE'); });

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
