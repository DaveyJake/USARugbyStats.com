<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchName extends AbstractHelper
{
    public function __invoke(Match $match)
    {
        return (string) $match;
    }
}
