<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Union;
use UsaRugbyStats\Competition\Traits\UnionServiceTrait;

class UnionLink extends AbstractHelper
{
    use UnionServiceTrait;

    public function __invoke($obj, $format = 'default', array $options = array())
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

        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/union-link/' . $format,
            [ 'union' => $union, 'options' => $options ]
        );
    }
}
