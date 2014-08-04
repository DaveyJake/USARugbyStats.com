<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.team.events.change' passes for either side of the match
 *   - Toggles match.teams.(A|H).events flag on (if not exlicitly off already)
 *
 * Otherwise
 *   - Toggles match.teams.(A|H).events flag off
 */
class CanChangeTeamEvents extends AbstractRule
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
        $canModifyASide = false;

        foreach (['H','A'] as $key) {
            $side = $e->getParams()->entity->getTeam($key);
            if (! $side instanceof MatchTeam) {
                continue;
            }

            if ( $this->getAuthorizationService()->isGranted('competition.competition.match.team.events.change', $side) ) {
                $canModifyASide = true;
                break;
            }
        }

        if ($canModifyASide) {
            $this->enableTeamEventsChange($e->getParams());
        } else {
            $this->disableTeamEventsChange($e->getParams());
        }
    }

}
