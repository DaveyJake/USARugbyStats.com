<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionName extends AbstractHelper
{
    public function __invoke(Competition $comp)
    {
        return $comp->getName();
    }
}
