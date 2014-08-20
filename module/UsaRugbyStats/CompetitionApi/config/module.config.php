<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_competition-api_competition_match' => 'UsaRugbyStats\CompetitionApi\Controller\CompetitionMatchController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'usarugbystats_competition-api_competition_match' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/competition/:cid/match[/:id]',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-api_competition_match',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),

    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usarugbystats_competition-api_*' => array('member'),
            ),
        ),
    ),
);
