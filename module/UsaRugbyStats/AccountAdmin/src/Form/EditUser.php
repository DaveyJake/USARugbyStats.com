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

        if (!$this->has('userId')) {
            $this->add(array(
                'name' => 'userId',
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                    'type' => 'hidden'
                ),
            ));
        }
        
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
