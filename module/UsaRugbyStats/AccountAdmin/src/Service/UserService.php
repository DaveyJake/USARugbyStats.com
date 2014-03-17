<?php
namespace UsaRugbyStats\AccountAdmin\Service;

use ZfcUserAdmin\Service\User as ZfcUserAdminUserService;
use Zend\Form\Form;
use ZfcUser\Entity\UserInterface;

class UserService extends ZfcUserAdminUserService
{
    protected $availableRoleAssignments;
    
    public function create(Form $form, array $data)
    {
        $this->populateRoleAssignmentInputDataWithEntityClassNames($data);
        return parent::create($form, $data);        
    }
    
    public function edit(Form $form, array $data, UserInterface $user)
    {
        $this->populateRoleAssignmentInputDataWithEntityClassNames($data);
        return parent::edit($form, $data, $user);        
    }
    
    protected function populateRoleAssignmentInputDataWithEntityClassNames(&$data)
    {
        if ( ! isset($data['roleAssignments']) || count($data['roleAssignments']) == 0 ) {
            $data['roleAssignments'] = array();
        }
        
        // Inject the entity class name into the POST request data
        // so that NonuniformCollection knows what entity to create
        $types = $this->getAvailableRoleAssignments();
        foreach ( $data['roleAssignments'] as $k=>$v ) {
            if ( isset($types[strtolower($v['type'])]) ) {
                $data['roleAssignments'][$k]['__class__'] = $types[strtolower($v['type'])]['entity_class'];
            }
        }
    }
    
    public function setAvailableRoleAssignments($set)
    {
        $this->availableRoleAssignments = $set;
        return $this;
    }
    
    public function getAvailableRoleAssignments()
    {
        return $this->availableRoleAssignments;
    }

}