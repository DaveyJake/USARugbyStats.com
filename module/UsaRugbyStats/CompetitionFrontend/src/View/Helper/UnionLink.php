<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Union;

class UnionLink extends AbstractHelper
{
    public function __invoke(Union $union)
    {
        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/union-link/default',
            [ 'union' => $union ]
        );
    }
}
