<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.team.change' passes:
 *   - Toggles match.details.change flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.team.change' fails:
 *   - Toggles match.details.change flag off
 */
class CanChangeTeam extends AbstractRule
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

            if ( $this->getAuthorizationService()->isGranted('competition.competition.match.team.change', $side) ) {
                $method = "enableSide{$key}TeamChange";
                $this->{$method}($e->getParams());
            } else {
                $method = "disableSide{$key}TeamChange";
                $this->{$method}($e->getParams());
            }
        }
    }

}
