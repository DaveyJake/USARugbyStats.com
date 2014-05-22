<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchTeamFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $collPlayers = $manager->get('usarugbystats_competition_competition_match_teamplayer_collectionfilter');
        $collEvents  = $manager->get('usarugbystats_competition_competition_match_teamevent_collectionfilter');

        $filter = new MatchTeamFilter(
            $collPlayers,
            $collEvents,
            $em->getRepository('UsaRugbyStats\Competition\Entity\Team')
        );

        return $filter;
    }
}
