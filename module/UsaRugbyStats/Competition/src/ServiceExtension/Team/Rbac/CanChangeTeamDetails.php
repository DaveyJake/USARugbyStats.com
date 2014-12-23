<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team\Rbac;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use Zend\EventManager\EventInterface;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;

class CanChangeTeamDetails implements ServiceExtensionInterface
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
        $base = [
            'team' => false,
            'team.remoteId' => false,
            'team.name' => false,
            'team.abbreviation' => false,
            'team.union' => false,
            'team.city' => false,
            'team.state' => false,
            'team.email' => false,
            'team.website' => false,
            'team.facebookHandle' => false,
            'team.twitterHandle' => false,
            'team.new_logo' => false,
            'team.new_cover_image' => false,
        ];

        // If they don't have the RBAC permission for changing this team, disallow everything
        if ( ! $this->getAuthorizationService()->isGranted('competition.team.update', $e->getParams()->entity) ) {
            $this->applyFlags($e->getParams()->flags, $base);

            return;
        }

        if ( $this->getAuthorizationService()->getIdentity()->hasRole('super_admin') ) {
            array_walk($base, function (&$item) { $item = true; });
            $this->applyFlags($e->getParams()->flags, $base);

            return;
        }

        // The baseline - what anyone with the `competition.team.update` can change
        $base['team'] = true;
        $base['team.city'] = true;
        $base['team.state'] = true;
        $base['team.email'] = true;
        $base['team.website'] = true;
        $base['team.facebookHandle'] = true;
        $base['team.twitterHandle'] = true;

        // If they have the RBAC permission for changing the union, allow it
        if ( $this->getAuthorizationService()->isGranted('competition.team.update.union', $e->getParams()->entity) ) {
            $base['team.union'] = true;
        }

        $this->applyFlags($e->getParams()->flags, $base);
    }

    protected function applyFlags($objFlags, $toApply)
    {
        foreach ($toApply as $key => $value) {
            $objFlags->{$key} = $value;
        }
    }
}
