<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $fs = $manager->get('usarugbystats_competition_competition_match_team_inputfilter');
        $repo = $manager->get('zfcuser_doctrine_em')->getRepository('UsaRugbyStats\Competition\Entity\Location');

        $filter = new MatchFilter($fs, $repo);

        return $filter;
    }
}
