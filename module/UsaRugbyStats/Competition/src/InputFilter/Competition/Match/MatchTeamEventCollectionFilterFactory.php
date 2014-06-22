<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LdcZendFormCTI\InputFilter\NonuniformCollectionInputFilter;

class MatchTeamEventCollectionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $filter = new NonuniformCollectionInputFilter();
        $filter->setDiscriminatorFieldName('event');
        $filter->setInputFilter(array(
            'card' => $manager->get('usarugbystats_competition_competition_match_teamevent_cardinputfilter'),
            'sub' => $manager->get('usarugbystats_competition_competition_match_teamevent_subinputfilter'),
            'score' => $manager->get('usarugbystats_competition_competition_match_teamevent_scoreinputfilter'),
        ));

        return $filter;
    }
}
