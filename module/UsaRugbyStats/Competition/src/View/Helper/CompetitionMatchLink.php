<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchLink extends AbstractHelper
{
    public function __invoke(Match $match, $format = 'default')
    {
        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/competition-match-link/' . $format,
            [ 'match' => $match ]
        );
    }
}
