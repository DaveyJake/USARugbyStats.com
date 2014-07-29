<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;

class TeamName extends AbstractHelper
{
    use TeamServiceTrait;

    public function __invoke($obj)
    {
        $team = null;
        if ($obj instanceof Team) {
            $team = $obj;
        } elseif ($obj instanceof MatchTeam) {
            $team = $obj->getTeam();
        } elseif ( ctype_digit(trim($obj)) ) {
            $team = $this->getTeamService()->findByID($obj);
        }
        if (! $team instanceof Team) {
            return;
        }

        return $team->getName();
    }
}
