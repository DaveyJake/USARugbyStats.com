<?php
return array(
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'options' => array(
                    'route' => '/account',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
    ),
    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function($sm) {
                return $sm->get('doctrine.authenticationservice.orm_default');
            },
            'UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup' => 'UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignupFactory',
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
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
    ),
    
    'zfc_rbac' => array(
    	'role_provider' => array(
    	    'ZfcRbac\Role\ObjectRepositoryRoleProvider' => array(
    	        'object_manager' 		=> 'zfcuser_doctrine_em',
    	        'class_name'     		=> 'UsaRugbyStats\Account\Entity\Rbac\Role',
    	        'role_name_property' 	=> 'name'
    	    ),
        ),
    ),
    'data-fixture' => [
        'UsaRugbyStats_Account' => __DIR__ . '/../src/Fixtures',
    ]
);
