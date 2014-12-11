<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionLink extends AbstractHelper
{
    public function __invoke(Competition $competition, $format = 'default', array $options = array())
    {
        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/competition-link/' . $format,
            [ 'competition' => $competition, 'options' => $options ]
        );
    }
}
