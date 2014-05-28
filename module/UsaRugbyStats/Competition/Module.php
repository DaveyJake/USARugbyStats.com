<?php
namespace UsaRugbyStats\Competition;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {

        $sm = $e->getApplication()->getServiceManager();
        if ( $sm->has('auditService') ) {
            $auditService = $sm->get('auditService');

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
            $em->attach('create.save', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_CREATE'); }, 2);
            $em->attach('update.save', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_UPDATE'); }, 2);
            $em->attach('remove', function ($e) use ($auditService) { $auditService->setComment('COMPETITION_COMPMATCH_DELETE'); });
        }

        $em = $sm->get('usarugbystats_competition_competition_match_service')->getEventManager();
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_lockcompetitionmatchwhencompleted'));
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_populatecompetitionmatchnonuniformcollection'));
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_emptycompetitionmatchcollections'));
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_removeunusedrosterslotsfromcompetitionmatch'));
        $em->attachAggregate($sm->get('usarugbystats_competition_listener_removeexistingsignaturesfromcompetitionmatch'));
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
