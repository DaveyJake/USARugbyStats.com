<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DivisionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $ifTeamMembership = $manager->get('usarugbystats_competition_competition_teammembership_inputfilter');

        $filter = new DivisionFilter($ifTeamMembership);

        return $filter;
    }
}
