<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DivisionFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $repo = $em->getRepository('UsaRugbyStats\Competition\Entity\Competition');

        $filter = new DivisionFilter($em, $repo);

        return $filter;
    }
}
