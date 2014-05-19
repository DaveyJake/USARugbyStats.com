<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchTeamFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $fs = $manager->get('usarugbystats_competition_competition_match_teamplayer_inputfilter');

        $filter = new MatchTeamFilter(
            $fs,
            $em->getRepository('UsaRugbyStats\Competition\Entity\Team')
        );

        return $filter;
    }
}