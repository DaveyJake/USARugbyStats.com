<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;

class EnforceManagedCompetitionsAssertion implements AssertionInterface
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
        if (! $context instanceof Competition) {
            return false;
        }

        $person = $authorization->getIdentity();
        if (! $person instanceof Account) {
            return false;
        }
        if ( $person->hasRole('super_admin') ) {
            return true;
        }

        $role = $person->getRoleAssignment('competition_admin');
        if (! $role instanceof CompetitionAdmin) {
            return false;
        }

        return $role->getManagedCompetitions()->contains($context);
    }
}
