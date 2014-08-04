<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.team.roster.change' passes:
 *   - Toggles match.teams.(A|H).players flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.team.roster.change' fails:
 *   - Toggles match.teams.(A|H).players flag off
 */
class CanChangeTeamRoster extends AbstractRule
{
    use AuthorizationServiceAwareTrait;

    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }
        if ( ! isset($e->getParams()->entity) ) {
            return false;
        }
        $teams = $e->getParams()->entity->getTeams();
        if ( empty($teams) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach (['H','A'] as $key) {
            $side = $e->getParams()->entity->getTeam($key);
            if (! $side instanceof MatchTeam) {
                continue;
            }

            if ( $this->getAuthorizationService()->isGranted('competition.competition.match.team.roster.change', $side) ) {
                $method = "enableSide{$key}TeamRosterChange";
                $this->{$method}($e->getParams());
            } else {
                $method = "disableSide{$key}TeamRosterChange";
                $this->{$method}($e->getParams());
            }
        }
    }

}
