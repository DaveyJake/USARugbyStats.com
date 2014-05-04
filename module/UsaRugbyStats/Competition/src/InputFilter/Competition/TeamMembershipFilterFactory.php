<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamMembershipFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');

        $filter = new TeamMembershipFilter($em->getRepository('UsaRugbyStats\Competition\Entity\Team'));

        return $filter;
    }
}
