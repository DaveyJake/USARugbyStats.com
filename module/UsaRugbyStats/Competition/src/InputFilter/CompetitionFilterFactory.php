<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompetitionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $repo = $em->getRepository('UsaRugbyStats\Competition\Entity\Competition');
        $ifDivision = $manager->get('usarugbystats_competition_competition_division_inputfilter');

        $filter = new CompetitionFilter($em, $repo, $ifDivision);

        return $filter;
    }
}
