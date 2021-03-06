<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_accountadmin_accountsearch' => 'UsaRugbyStats\AccountAdmin\Controller\AccountSearchController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'zfcuseradmin' => array(
                        'child_routes' =>array(
                            'search' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/search[/:p]',
                                    'defaults' => array(
                                        'controller' => 'usarugbystats_accountadmin_accountsearch',
                                        'action'     => 'search',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'zfcuseradmin_createuser_form' => 'UsaRugbyStats\AccountAdmin\Form\CreateUserFactory',

            'zfcuseradmin_edituser_form'   => 'UsaRugbyStats\AccountAdmin\Form\EditUserFactory',
            'zfcuseradmin_edituser_filter' => 'UsaRugbyStats\AccountAdmin\Form\EditUserFilterFactory',

            'UsaRugbyStats\AccountAdmin\Service\UserService' => 'UsaRugbyStats\AccountAdmin\Service\UserServiceFactory',

            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElementFactory',

            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldsetFactory',
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldsetFactory',
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldsetFactory',
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldsetFactory',
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldsetFactory',
        ),
        'shared' => array(
            // SL-managed form elements should not be singleton
            'zfcuseradmin_createuser_form' => false,
            'zfcuseradmin_edituser_form' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset' => false,
            'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset' => false,
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'zfc-user-admin/user-admin/list'  => __DIR__ . '/../view/zfc-user-admin/user-admin/list.phtml',
            'zfc-user-admin/user-admin/create'  => __DIR__ . '/../view/zfc-user-admin/user-admin/create.phtml',
            'zfc-user-admin/user-admin/edit'    => __DIR__ . '/../view/zfc-user-admin/user-admin/edit.phtml',
            'zfc-user-admin/user-admin/pagination_userlist.phtml' => __DIR__ . '/../view/zfc-user-admin/user-admin/pagination_userlist.phtml',

            'usa-rugby-stats/account-admin/account-search/list' => __DIR__ . '/../view/usa-rugby-stats/account-admin/account-search/list.phtml',
            'usa-rugby-stats/account-admin/account-search/pagination' => __DIR__ . '/../view/usa-rugby-stats/account-admin/account-search/pagination.phtml',

            'usa-rugby-stats/account-admin/role-assignments' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments.phtml',
            'usa-rugby-stats/account-admin/role-assignments/common' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/common.phtml',
            'usa-rugby-stats/account-admin/role-assignments/member' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/member.phtml',
            'usa-rugby-stats/account-admin/role-assignments/member/record' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/member/record.phtml',
            'usa-rugby-stats/account-admin/role-assignments/team_admin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/team_admin.phtml',
            'usa-rugby-stats/account-admin/role-assignments/competition_admin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/competition_admin.phtml',
            'usa-rugby-stats/account-admin/role-assignments/union_admin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/union_admin.phtml',
            'usa-rugby-stats/account-admin/role-assignments/super_admin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/super_admin.phtml',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
