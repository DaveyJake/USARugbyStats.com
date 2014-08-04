<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchTeamAssertion;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.signatures.change' passes:
 *   - Toggles match.details.change flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.signatures.change' fails:
 *   - Toggles match.details.change flag off
 */
class CanChangeSignatureForCoach extends AbstractRule
{
    use AuthorizationServiceAwareTrait;

    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->entity) || ! $e->getParams()->entity->getId() ) {
            return false;
        }
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $person = $this->getAuthorizationService()->getIdentity();
        $assertion = new EnforceManagedCompetitionMatchTeamAssertion();

        foreach (['H','A'] as $sideKey) {
            $side = $e->getParams()->entity->getTeam($sideKey);
            if (! $side instanceof MatchTeam) {
                continue;
            }
            if ( $assertion->assert($this->getAuthorizationService(), $side) ) {
                if ( ! $e->getParams()->flags->{'match.signatures.allowed_types'}->has($sideKey.'C') ) {
                    $e->getParams()->flags->{'match.signatures.allowed_types'}->push($sideKey.'C');
                }
            }
        }
    }

}
