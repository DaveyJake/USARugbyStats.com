<?php
return array(
    'audit' => array(
        'datetimeFormat' => 'r',
        'paginatorLimit' => 20,

        'userEntityClassName' => 'UsaRugbyStats\Account\Entity\Account',
        'authenticationService' => 'zfcuser_auth_service',

        'tableNamePrefix' => '',
        'tableNameSuffix' => '_audit',
        'revisionTableName' => 'Revision',
        'revisionEntityTableName' => 'RevisionEntity',
    ),

    'router' => array(
        'routes' => array(
            'audit' => array(
                'options' => array(
                    'route' => '/admin/audit',
                ),
            ),
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'audit' => array(
                'label' => 'Entity Audit Log',
                'route' => 'audit',
            ),
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'audit' => [ 'super_admin' ],
                'audit/*' => [ 'super_admin' ],
            ),
        ),
    ),
);
