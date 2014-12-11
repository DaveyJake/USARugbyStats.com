<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchLocationLink extends AbstractHelper
{
    public function __invoke(Match $match, $format = 'default', array $options = array())
    {
        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/competition-match-location-link/' . $format,
            [ 'match' => $match, 'options' => $options ]
        );
    }
}
