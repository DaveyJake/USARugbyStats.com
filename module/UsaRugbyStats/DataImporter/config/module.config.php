<?php
return array(
    'usarugbystats' => array(
        'data-importer' => array(
            'tasks'     => array(),
            'fixtures'  => array(),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'usarugbystats_data-importer_task-service' => 'UsaRugbyStats\DataImporter\Service\TaskServiceFactory',
            'usarugbystats_data-importer_fixture-service' => 'UsaRugbyStats\DataImporter\Service\FixtureServiceFactory',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'usa-rugby-stats_data-importer_task-runner' => array(
                    'options' => array(
                        'route' => 'data-importer run-task --task= [--file=]',
                        'defaults' => array(
                            'controller' => 'usa-rugby-stats_data-importer_task-runner',
                            'action'     => 'execute',
                        ),
                    ),
                ),
                'usa-rugby-stats_data-importer_fixture-runner' => array(
                    'options' => array(
                        'route' => 'data-importer run-fixtures --group=',
                        'defaults' => array(
                            'controller' => 'usa-rugby-stats_data-importer_fixture-runner',
                            'action'     => 'execute',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
       'factories' => array(
           'usa-rugby-stats_data-importer_task-runner' => 'UsaRugbyStats\DataImporter\Controller\TaskRunnerFactory',
           'usa-rugby-stats_data-importer_fixture-runner' => 'UsaRugbyStats\DataImporter\Controller\FixtureRunnerFactory',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usa-rugby-stats_data-importer_task-runner' => [ 'super_admin' ],
                'usa-rugby-stats_data-importer_fixture-runner' => [ 'super_admin' ],
            ),
        ),
    ),
);
