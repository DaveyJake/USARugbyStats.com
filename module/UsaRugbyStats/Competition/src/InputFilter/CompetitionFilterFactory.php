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
        
        $filter = new CompetitionFilter($em, $repo);
        return $filter;
    }
}