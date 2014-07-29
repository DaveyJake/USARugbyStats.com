<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;

class EnforceManagedCompetitionMatchTeamAssertion implements AssertionInterface
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
        if (! $context instanceof MatchTeam) {
            return true;
        }

        $match = $context->getMatch();

        // They must be allowed to edit this match
        if ( ! $authorization->isGranted('competition.competition.match.update', $match) ) {
            return false;
        }

        $person = $authorization->getIdentity();
        if (! $person instanceof Account) {
            return false;
        }
        if ( $person->hasRole('super_admin') ) {
            return true;
        }

        $isAllowed = false;

        $role = $person->getRoleAssignment('team_admin');
        if ($role instanceof TeamAdmin) {
            $isAllowed = $isAllowed || $role->hasManagedTeam($context->getTeam());
        }
        if ($isAllowed) {
            return true;
        }

        $role = $person->getRoleAssignment('union_admin');
        if ($role instanceof UnionAdmin) {
            $isAllowed = $isAllowed || $role->hasManagedTeam($context->getTeam());
        }
        if ($isAllowed) {
            return true;
        }

        $role = $person->getRoleAssignment('competition_admin');
        if ($role instanceof CompetitionAdmin) {
            $isAllowed = $isAllowed || $role->hasManagedCompetition($match->getCompetition());
        }

        return $isAllowed;
    }
}
