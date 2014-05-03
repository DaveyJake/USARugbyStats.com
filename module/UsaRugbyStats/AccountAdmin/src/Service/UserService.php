<?php
namespace UsaRugbyStats\AccountAdmin\Service;

use ZfcUserAdmin\Service\User as ZfcUserAdminUserService;
use Zend\Form\Form;
use UsaRugbyStats\Account\Entity\Account as AccountEntity;
use ZfcUser\Entity\UserInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

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

        $filterCamelToUnderscore = new CamelCaseToUnderscore();

        // Inject the entity class name into the POST request data
        // so that NonuniformCollection knows what entity to create
        $types = $this->getAvailableRoleAssignments();
        foreach ($data['roleAssignments'] as $k=>$v) {
            $key = strtolower($filterCamelToUnderscore->filter($v['type']));
            if ( ! isset($types[$key]) ) {
                unset($data['roleAssignments'][$k]);
                continue;
            }
            $data['roleAssignments'][$k]['__class__'] = $types[$key]['entity_class'];
            unset($data['roleAssignments'][$k]['type']);
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
