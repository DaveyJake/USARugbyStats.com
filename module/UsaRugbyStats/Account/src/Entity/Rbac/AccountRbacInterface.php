<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use Doctrine\Common\Collections\Collection;

interface AccountRbacInterface
{

    public function getRoleAssignments();

    public function getRoleAssignment($rolename);

    public function setRoleAssignments(Collection $roleAssignments);

    public function addRoleAssignment(RoleAssignment $ra);

    public function hasRoleAssignment(RoleAssignment $ra);

    public function hasRole($role);

}
