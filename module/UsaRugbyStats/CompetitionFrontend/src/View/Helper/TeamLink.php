<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;

class TeamLink extends AbstractHelper
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

        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/team-link/default',
            [ 'team' => $team ]
        );
    }
}