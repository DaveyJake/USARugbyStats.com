<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Union;
use Zend\View\Helper\EscapeHtml;

class UnionName extends AbstractHelper
{
    public function __invoke(Union $union)
    {
        return $union->getName();
    }
}