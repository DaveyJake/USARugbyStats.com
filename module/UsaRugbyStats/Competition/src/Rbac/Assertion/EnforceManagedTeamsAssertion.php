<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;

class EnforceManagedTeamsAssertion implements AssertionInterface
{
    /**
     * Check if this assertion is true
     *
     * @param AuthorizationService $authorization
     *
     * @return bool
     */
    public function assert(AuthorizationService $authorization, $context = null)
    {
        // If there is no context we assume we're in create mode
        // (anything goes in create mode!)
        if (! $context instanceof Team) {
            return true;
        }

        $person = $authorization->getIdentity();
        if (! $person instanceof Account) {
            return false;
        }
        if ( $person->hasRole('super_admin') ) {
            return true;
        }

        $isAllowed = false;

        // Check their TeamAdmin role for this team
        $role = $person->getRoleAssignment('team_admin');
        if ( $role instanceof TeamAdmin) {
            $isAllowed = $isAllowed || $role->getManagedTeams()->contains($context);
        }

        // Check the unions of their UnionAdmin role for this team
        $role = $person->getRoleAssignment('union_admin');
        if ( $role instanceof UnionAdmin) {
            $isAllowed = $isAllowed || $role->getManagedTeams()->contains($context);
        }

        return $isAllowed;
    }
}
