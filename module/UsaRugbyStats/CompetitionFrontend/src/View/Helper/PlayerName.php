<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;

class PlayerName extends AbstractHelper
{
    public function __invoke($obj)
    {
        $player = null;
        if ($obj instanceof AccountInterface) {
            $player = $obj;
        } elseif ($obj instanceof MatchTeamPlayer) {
            $player = $obj->getPlayer();
        }
        if ( is_null($player) ) {
            return;
        }

        return $player->getDisplayName();
    }
}
