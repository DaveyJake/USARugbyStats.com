<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;

class PlayerLink extends AbstractHelper
{
    public function __invoke($obj)
    {
        $player = NULL;
        if ( $obj instanceof AccountInterface ) {
            $player = $obj;
        } elseif ( $obj instanceof MatchTeamPlayer ) {
            $player = $obj->getPlayer();
        }
        if ( is_null($player) ) {
            return;
        }

        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/player-link/default',
            [ 'player' => $player ]
        );
    }
}