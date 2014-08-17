<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;

class TeamCoverImageUrl extends AbstractHelper
{
    protected $basePattern = '/assets/img/teamcoverimages/%s.png';

    public function __invoke($obj)
    {
        if ($obj instanceof Team) {
            $teamid = $obj->getId();
        } elseif ($obj instanceof MatchTeam) {
            $teamid = $obj->getTeam()->getId();
        } elseif ( ctype_digit(trim($obj)) ) {
            $teamid = $obj;
        }
        if (! isset($teamid)) {
            return sprintf($this->basePattern, 'notfound');
        }

        $url      = sprintf($this->basePattern, $teamid);
        $filename = 'public' . $url;

        if ( ! file_exists($filename) ) {
            return sprintf($this->basePattern, 'notfound');
        }

        return $url;
    }
}
