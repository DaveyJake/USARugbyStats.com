<?php
return array(

    'usarugbystats' => array(
        'data-importer' => array(
            'tasks' => array(
                'factories' => array(
                    'Account.ImportAccounts' => 'UsaRugbyStats\Account\DataImporter\ImportAccountsTaskFactory',
                ),
            ),
            'fixtures' => array(
                'testing' => array(
                    'account_accounts' => array(
                        'file' => __DIR__ . '/../data/fixtures/testing/accounts.php',
                        'task' => 'Account.ImportAccounts',
                        'dependencies' => [ 'testing.competition_unions', 'testing.competition_teams', 'testing.competition_competitions' ],
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'options' => array(
                    'route' => '/account',
                ),
            ),
            'usarugbystats_account-api_account' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/account[/:id]',
                    'constraints' => array(
                        'id' => '\d{1,}',
                    ),
                    'defaults' => array(
                        'controller' => 'usarugbystats_account-api_account',
                    ),
                ),
            ),
            'usarugbystats_account-api_session_sid' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/session/sid',
                    'defaults' => array(
                        'controller' => 'usarugbystats_account-api_session',
                        'action' => 'sid',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_account-api_account' => 'UsaRugbyStats\Account\Controller\AccountApiController',
            'usarugbystats_account-api_session' => 'UsaRugbyStats\Account\Controller\SessionController',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function ($sm) { return $sm->get('doctrine.authenticationservice.orm_default'); },
            'UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup' => 'UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignupFactory',
            'UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation' => 'UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociationFactory',

            // Override ZfcUser mapper from ZfcUserDoctrineORM with one that triggers events on insert and update
            'zfcuser_user_mapper' => 'UsaRugbyStats\Account\ZfcUser\UserMapperFactory',

            // Override factory for ZfcUser\Form\Register so we can hijack the validator and meld it to our will
            'zfcuser_register_form' => 'UsaRugbyStats\Account\ZfcUser\UserRegisterFormFactory',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'zfc-user/user/login' => __DIR__ . '/../view/zfc-user/user/login.phtml',
        ),
        'template_path_stack' => array(
            'UsaRugbyStats\Account' => __DIR__ . '/../view',
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'user' => 'UsaRugbyStats\Account\View\Helper\UserHelperFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'user' => 'UsaRugbyStats\Account\Controller\Plugin\UserPluginFactory',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'usarugbystats_account_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/doctrine/self'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'UsaRugbyStats\Account\Entity'  => 'usarugbystats_account_entity'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'zfcuser_doctrine_em',
                'identity_class' => 'UsaRugbyStats\Account\Entity\Account',
                'identity_property' => 'email',
                'credential_property' => 'password',
            ),
        ),
        'entity_resolver' => array(
            'orm_default' => array(
                'resolvers' => array(
                    'UsaRugbyStats\Application\Entity\AccountInterface' => 'UsaRugbyStats\Account\Entity\Account',
                ),
            ),
        ),
        'fixture' => array(
            'UsaRugbyStats_Account_fixture' => __DIR__ . '/../src/Fixtures/Doctrine',
        ),
    ),

    'zfc_rbac' => array(
        'role_provider' => array(
            'ZfcRbac\Role\ObjectRepositoryRoleProvider' => array(
                'object_manager'        => 'zfcuser_doctrine_em',
                'class_name'             => 'UsaRugbyStats\Account\Entity\Rbac\Role',
                'role_name_property'    => 'name'
            ),
        ),
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usarugbystats_account-api_session_sid' => array('guest'),
                'usarugbystats_account-api_*' => array('member'),
            ),
        ),
    ),

    'audit' => array(
        'entities' => array(
            'UsaRugbyStats\Account\Entity\Account' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin' => [],
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin' => [],
        ),
    ),

    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);
