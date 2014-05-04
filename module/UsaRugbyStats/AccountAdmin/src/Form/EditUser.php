<?php
namespace UsaRugbyStats\AccountAdmin\Form;

use ZfcUserAdmin\Form\EditUser as ZfcUserAdminEditUserForm;

use ZfcUserAdmin\Options\UserCreateOptionsInterface;
use ZfcUser\Options\RegistrationOptionsInterface;

class EditUser extends ZfcUserAdminEditUserForm
{

    public function __construct($name = null, UserCreateOptionsInterface $createOptions, RegistrationOptionsInterface $registerOptions, $serviceManager)
    {
        parent::__construct($name, $createOptions, $registerOptions, $serviceManager);

        $rbacFieldset = $serviceManager->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement');
        $this->add($rbacFieldset);

        $filter = $serviceManager->get('zfcuseradmin_edituser_filter');
    }

}
