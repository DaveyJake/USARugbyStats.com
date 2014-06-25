<?php
namespace UsaRugbyStats\AccountAdmin\Service;

use ZfcUserAdmin\Service\User as ZfcUserAdminUserService;
use Zend\Form\Form;
use UsaRugbyStats\Account\Entity\Account as AccountEntity;
use ZfcUser\Entity\UserInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;

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
        $this->populateRoleAssignmentInputDataWithEntityClassNames($data, $user);

        return parent::edit($form, $data, $user);
    }

    protected function populateRoleAssignmentInputDataWithEntityClassNames(&$data, UserInterface $user = NULL)
    {
        if ( ! isset($data['roleAssignments']) || count($data['roleAssignments']) == 0 ) {
            // @HACK to fix GH-15 (Can't empty an existing Collection)
            if ($user instanceof AccountEntity) {
                $user->removeRoleAssignments($user->getRoleAssignments());
            }

            $data['roleAssignments'] = array();

            return;
        }

        $types = $this->getAvailableRoleAssignments();
        foreach ($data['roleAssignments'] as $k=>$v) {

            if ( !isset($v['type']) || empty($v['type']) ) {
                unset($data['roleAssignments'][$k]);
                continue;
            }

            $key = $v['type'];
            if ( ! isset($types[$key]) ) {
                unset($data['roleAssignments'][$k]);
                continue;
            }

            // @HACK to fix GH-15 (Can't empty an existing Collection)
            if ($user instanceof AccountEntity) {
                switch ($key) {
                    case 'competition_admin':
                    {
                        if ( ! isset($data['roleAssignments'][$k]['managedCompetitions']) || count($data['roleAssignments'][$k]['managedCompetitions']) == 0 ) {
                            $obj = $user->getRoleAssignment($types[$key]['name']);
                            if ($obj instanceof CompetitionAdmin) {
                                $obj->removeManagedCompetitions($obj->getManagedCompetitions());
                            }
                        }
                        break;
                    }
                    case 'team_admin':
                    {
                        if ( ! isset($data['roleAssignments'][$k]['managedTeams']) || count($data['roleAssignments'][$k]['managedTeams']) == 0 ) {
                            $obj = $user->getRoleAssignment($types[$key]['name']);
                            if ($obj instanceof TeamAdmin) {
                                $obj->removeManagedTeams($obj->getManagedTeams());
                            }
                        }
                        break;
                    }
                    case 'union_admin':
                    {
                        if ( ! isset($data['roleAssignments'][$k]['managedUnions']) || count($data['roleAssignments'][$k]['managedUnions']) == 0 ) {
                            $obj = $user->getRoleAssignment($types[$key]['name']);
                            if ($obj instanceof UnionAdmin) {
                                $obj->removeManagedUnions($obj->getManagedUnions());
                            }
                        }
                        break;
                    }
                }
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
