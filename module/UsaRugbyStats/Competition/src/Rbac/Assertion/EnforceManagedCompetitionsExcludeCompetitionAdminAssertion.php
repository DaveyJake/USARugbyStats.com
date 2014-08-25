<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\Competition\Entity\Competition;

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

        // If there is no context we assume we're in create mode
        // (anything goes in create mode!)
        if (! $context instanceof Competition) {
            return true;
        }

        $role = $authorization->getIdentity()->getRoleAssignment('competition_admin');
        if ($role instanceof CompetitionAdmin && $role->hasManagedCompetition($context)) {
            $isAllowed = false;
        }

        return $isAllowed;
    }
}
