<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\AbstractRule;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;
use Zend\EventManager\EventInterface;

/**
 * If check of RBAC permission 'competition.competition.match.signatures.change' passes:
 *   - Toggles match.details.change flag on (if not exlicitly off already)
 *
 * If check of RBAC permission 'competition.competition.match.signatures.change' fails:
 *   - Toggles match.details.change flag off
 */
class CanChangeSignatures extends AbstractRule
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
        if ( $this->getAuthorizationService()->isGranted('competition.competition.match.signatures.change', @$e->getParams()->entity) ) {
            $this->enableSignatureChange($e->getParams());
        } else {
            $this->disableSignatureChange($e->getParams());
        }

        // If the user is competition or super admin they can change all the types
        if ( $e->getParams()->flags->{'match.signatures'}->is_on() ) {
           $user = $this->getAuthorizationService()->getIdentity();
           if ( $user->hasRole('competition_admin') || $user->hasRole('super_admin') ) {
               $e->getParams()->flags->{'match.signatures.allowed_types'} = ['HC','AC','REF','NR4'];
           }
        }
    }

}
