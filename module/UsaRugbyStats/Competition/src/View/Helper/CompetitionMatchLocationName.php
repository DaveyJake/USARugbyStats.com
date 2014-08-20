<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchLocationName extends AbstractHelper
{
    public function __invoke(Match $match)
    {
        return $this->getView()->ursLocationName($match->getLocation());
    }
}
