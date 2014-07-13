<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;

class CompetitionMatchName extends AbstractHelper
{
    public function __invoke(Match $match)
    {
        return (string)$match;
    }
}
