<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchTeamPlayerCollectionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $filter = new MatchTeamPlayerCollectionFilter(
            $manager->get('usarugbystats_competition_competition_match_teamplayer_inputfilter')
        );

        return $filter;
    }
}
