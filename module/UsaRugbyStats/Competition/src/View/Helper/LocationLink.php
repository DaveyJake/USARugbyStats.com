<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Location;
use UsaRugbyStats\Competition\Traits\LocationServiceTrait;

class LocationLink extends AbstractHelper
{
    use LocationServiceTrait;

    public function __invoke($obj)
    {
        $location = null;
        if ($obj instanceof Location) {
            $location = $obj;
        } elseif ( ctype_digit(trim($obj)) ) {
            $location = $this->getLocationService()->findByID($obj);
        }
        if (! $location instanceof Location) {
            return "&gt; No Location Specified &lt;";
        }

        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/location-link/default',
            [ 'location' => $location ]
        );
    }
}
