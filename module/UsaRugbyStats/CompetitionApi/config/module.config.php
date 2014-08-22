<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_competition-api_competition_match' => 'UsaRugbyStats\CompetitionApi\Controller\CompetitionMatchController',
            'usarugbystats_competition-api_competition_match_event' => 'UsaRugbyStats\CompetitionApi\Controller\CompetitionMatchEventController',
            'usarugbystats_competition-api_competition_match_prepare-form' => 'UsaRugbyStats\CompetitionApi\Controller\CompetitionMatchPrepareFormController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'usarugbystats_competition-api_competition_match_prepare-form' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/competition/:cid/match[/:id]/prepare-form',
                    'constraints' => array(
                        'cid' => '\d{1,}',
                        'id' => '\d{1,}',
                    ),
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-api_competition_match_prepare-form',
                        'action'     => 'prepare-form',
                    ),
                ),
                'may_terminate' => true,
            ),
            'usarugbystats_competition-api_competition_match_events' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/competition/:cid/match/:mid/event[/:id]',
                    'constraints' => array(
                        'cid' => '\d{1,}',
                        'mid' => '\d{1,}',
                        'id' => '\d{1,}',
                    ),
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-api_competition_match_event',
                    ),
                ),
                'may_terminate' => true,
            ),
            'usarugbystats_competition-api_competition_match' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/competition/:cid/match[/:id]',
                    'constraints' => array(
                        'cid' => '\d{1,}',
                        'id' => '\d{1,}',
                    ),
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
                'usarugbystats_competition-api_*' => array('guest'),
            ),
        ),
    ),
);
