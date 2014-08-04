<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\Rules\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.details.change' passes:
 *   - Toggles match.details.change flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.details.change' fails:
 *   - Toggles match.details.change flag off

 */
class CanChangeDetails extends AbstractRule
{
    use AuthorizationServiceAwareTrait;

    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        if ( $this->getAuthorizationService()->isGranted('competition.competition.match.details.change', @$e->getParams()->entity) ) {
            $this->enableDetailsChange($e->getParams());
        } else {
            $this->disableDetailsChange($e->getParams());
        }
    }
}
