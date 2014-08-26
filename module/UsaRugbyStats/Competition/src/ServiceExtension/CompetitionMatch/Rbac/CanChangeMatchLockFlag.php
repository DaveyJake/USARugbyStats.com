<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin;

class CanChangeMatchLockFlag extends AbstractRule
{
    use AuthorizationServiceAwareTrait;

    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }
        if ( ! isset($e->getParams()->entity) || $e->getParams()->entity->getId() == null ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        if ( ! $this->getAuthorizationService()->isGranted('competition.competition.match.details.change', $e->getParams()->entity) ) {
            return;
        }

        $homeTeam = $e->getParams()->entity->getHomeTeam()->getTeam();
        $awayTeam = $e->getParams()->entity->getAwayTeam()->getTeam();

        $person = $this->getAuthorizationService()->getIdentity();
        if (! $person instanceof Account) {
            return false;
        }

        $isAllowed = false;

        $role = $person->getRoleAssignment('team_admin');
        if ($role instanceof TeamAdmin && ( $role->hasManagedTeam($homeTeam) || $role->hasManagedTeam($awayTeam) ) ) {
            $isAllowed = false;
        }

        $role = $person->getRoleAssignment('union_admin');
        if ($role instanceof UnionAdmin && ( $role->hasManagedTeam($homeTeam) || $role->hasManagedTeam($awayTeam) ) ) {
            $isAllowed = true;
        }

        $role = $person->getRoleAssignment('competition_admin');
        if ($role instanceof CompetitionAdmin && $role->hasManagedCompetition($e->getParams()->entity->getCompetition()) ) {
            $isAllowed = true;
        }

        $role = $person->getRoleAssignment('super_admin');
        if ( $role instanceof SuperAdmin ) {
            $isAllowed = true;
        }

        $isAllowed ? $e->getParams()->flags->{'match.isLocked'}->on()
                   : $e->getParams()->flags->{'match.isLocked'}->off();
    }
}
