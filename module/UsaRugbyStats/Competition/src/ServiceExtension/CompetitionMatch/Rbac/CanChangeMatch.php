<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.details.change' passes:
 *   - Toggles match.details.change flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.details.change' fails:
 *   - Toggles match.details.change flag off

 */
class CanChangeMatch extends AbstractRule
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

        return true;
    }

    public function execute(EventInterface $e)
    {
        if ( $this->getAuthorizationService()->isGranted('competition.competition.match.update', $e->getParams()->entity) ) {
            $this->enableMatchChange($e->getParams());
        } else {
            $this->disableMatchChange($e->getParams());
        }
    }
}
