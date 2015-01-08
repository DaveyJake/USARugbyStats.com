<?php
namespace UsaRugbyStats\Competition\Rbac\Assertion;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\Competition\Entity\Competition;

class EnforceManagedPlayerAssertion implements AssertionInterface
{
    /**
     * Check if this assertion is true
     *
     * @param AuthorizationService $authorization
     *
     * @return bool
     */
    public function assert(AuthorizationService $authorization, $player = null)
    {
        $admin = $authorization->getIdentity();
        if (! $admin instanceof AccountRbacInterface) {
            return false;
        }

        // SuperAdmin gets errrrthing
        if ( $admin->hasRole('super_admin') ) {
            return true;
        }

        // If we don't have RBAC data, short circuit
        if (! $player instanceof AccountRbacInterface) {
            return false;
        }

        $memberRole = $player->getRoleAssignment('member');
        if (! $memberRole instanceof Member) {
            return false;
        }

        // Load all the teams this player is a member of
        foreach ( $memberRole->getMemberships() as $membership ) {
            if (! $membership instanceof Team\Member) {
                continue;
            }
            $team = $membership->getTeam();
            if (! $team instanceof Team) {
                continue;
            }

            // If the admin can manage this team, short circuit w/ positive result
            // (check all applicable roles in ascending order of permissions
            $role = $admin->getRoleAssignment('competition_admin');
            if ($role instanceof CompetitionAdmin) {
                foreach ( $role->getManagedCompetitions() as $comp ) {
                    if (! $comp instanceof Competition) {
                        continue;
                    }
                    if ( $comp->hasTeam($team) ) {
                        return true;
                    }
                }
            }

            $role = $admin->getRoleAssignment('team_admin');
            if ($role instanceof TeamAdmin && $role->hasManagedTeam($team) ) {
                return true;
            }

            $role = $admin->getRoleAssignment('union_admin');
            if ($role instanceof UnionAdmin && $role->hasManagedTeam($team) ) {
                return true;
            }
        }

        return false;
    }
}
