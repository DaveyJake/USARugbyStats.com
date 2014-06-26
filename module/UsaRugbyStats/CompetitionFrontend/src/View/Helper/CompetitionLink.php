<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionLink extends AbstractHelper
{
    public function __invoke(Competition $competition)
    {
        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/competition-link/default',
            [ 'competition' => $competition ]
        );
    }
}
