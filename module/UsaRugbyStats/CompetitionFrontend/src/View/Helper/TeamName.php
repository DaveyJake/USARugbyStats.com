<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Zend\View\Helper\EscapeHtml;

class TeamName extends AbstractHelper
{
    public function __invoke($obj)
    {
        $team = NULL;
        if ( $obj instanceof Team ) {
            $team = $obj;
        } elseif ( $obj instanceof MatchTeam ) {
            $team = $obj->getTeam();
        }
        if ( is_null($team) ) {
            return;
        }

        return $team->getName();
    }
}