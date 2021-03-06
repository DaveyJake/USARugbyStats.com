<?php
$settings = array(

    // Don't automatically generate a password for users on create
    'create_user_auto_password' => false,

    // The ZfcUser mapper to use
    'user_mapper' => 'ZfcUserAdmin\Mapper\UserDoctrine',

    // List of elements to show in the List Users table
    'user_list_elements' => array(
        'Id' => 'id',
        'Username' => 'username',
        'Display Name' => 'displayName',
        'Email address' => 'email',
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcuseradmin' => $settings
);
