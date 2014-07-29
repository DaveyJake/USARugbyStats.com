<?php
namespace UsaRugbyStats\AccountAdmin\Form;

use ZfcUserAdmin\Form\CreateUser as ZfcUserAdminCreateUserForm;

use ZfcUserAdmin\Options\UserCreateOptionsInterface;
use ZfcUser\Options\RegistrationOptionsInterface;

class CreateUser extends ZfcUserAdminCreateUserForm
{

    public function __construct($name = null, UserCreateOptionsInterface $createOptions, RegistrationOptionsInterface $registerOptions, $serviceManager)
    {
        parent::__construct($name, $createOptions, $registerOptions, $serviceManager);

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'remoteId',
            'options' => array(
                'label' => 'Remote ID',
            ),
        ));

        $rbacFieldset = $serviceManager->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement');
        $this->add($rbacFieldset);

    }

}
