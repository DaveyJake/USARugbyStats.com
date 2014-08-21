<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;

class EnforceManagedCompetitionsExcludeCompetitionAdminAssertion extends EnforceManagedCompetitionsAssertion
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
        $isAllowed = parent::assert($authorization, $context);

        $role = $authorization->getIdentity()->getRoleAssignment('competition_admin');
        if ($role instanceof CompetitionAdmin && $role->hasManagedCompetition($context)) {
            $isAllowed = false;
        }

        return $isAllowed;
    }
}
