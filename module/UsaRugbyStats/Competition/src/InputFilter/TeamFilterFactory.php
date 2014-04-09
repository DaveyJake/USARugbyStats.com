<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $repo = $em->getRepository('UsaRugbyStats\Competition\Entity\Team');
        
        $filter = new TeamFilter($em, $repo);
        return $filter;
    }
}