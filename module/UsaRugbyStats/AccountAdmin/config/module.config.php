<?php
return array(
    
    'service_manager' => array(
	    'factories' => array(
	        'zfcuseradmin_createuser_form' => 'UsaRugbyStats\AccountAdmin\Form\CreateUserFactory',
	        
	        'zfcuseradmin_edituser_form'   => 'UsaRugbyStats\AccountAdmin\Form\EditUserFactory',
	        'zfcuseradmin_edituser_filter' => 'UsaRugbyStats\AccountAdmin\Form\EditUserFilterFactory',
	        
	        'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElementFactory',

	        'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\GuestFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\GuestFieldsetFactory',
	        'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldsetFactory',
	        'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldsetFactory',
        ),
        'shared' => array(
	    	'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement' => false,
	    ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            'zfc-user-admin/user-admin/create'  => __DIR__ . '/../view/zfc-user-admin/user-admin/create.phtml',
            'zfc-user-admin/user-admin/edit'    => __DIR__ . '/../view/zfc-user-admin/user-admin/edit.phtml',
            
            'usa-rugby-stats/account-admin/role-assignments' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments.phtml',
            'usa-rugby-stats/account-admin/role-assignments/common' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/common.phtml',
            'usa-rugby-stats/account-admin/role-assignments/teamadmin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/teamadmin.phtml',
            'usa-rugby-stats/account-admin/role-assignments/superadmin' => __DIR__ . '/../view/usa-rugby-stats/account-admin/role-assignments/superadmin.phtml',
        ),
    ),
);
