<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Union;
use UsaRugbyStats\Competition\Traits\UnionServiceTrait;

class UnionName extends AbstractHelper
{
    use UnionServiceTrait;

    public function __invoke($obj)
    {
        $union = null;
        if ($obj instanceof Union) {
            $union = $obj;
        } elseif ( ctype_digit(trim($obj)) ) {
            $union = $this->getUnionService()->findByID($obj);
        }
        if (! $union instanceof Union) {
            return "&gt; Not Found &lt;!";
        }

        return $union->getName();
    }
}
