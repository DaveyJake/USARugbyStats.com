<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Union;

class UnionName extends AbstractHelper
{
    public function __invoke(Union $union)
    {
        return $union->getName();
    }
}
