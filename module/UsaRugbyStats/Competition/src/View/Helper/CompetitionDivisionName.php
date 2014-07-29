<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class CompetitionDivisionName extends AbstractHelper
{
    public function __invoke(Division $div)
    {
        return $div->getName();
    }
}
