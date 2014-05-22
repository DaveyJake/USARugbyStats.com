<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchTeamEventCollectionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $filter = new MatchTeamEventCollectionFilter();
        $filter->setInputFilter(array(
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent' => $manager->get('usarugbystats_competition_competition_match_teamevent_cardinputfilter'),
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent' => $manager->get('usarugbystats_competition_competition_match_teamevent_subinputfilter'),
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent' => $manager->get('usarugbystats_competition_competition_match_teamevent_scoreinputfilter'),
        ));

        return $filter;
    }
}
