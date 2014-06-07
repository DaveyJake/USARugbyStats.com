<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');
        $repo = $em->getRepository('UsaRugbyStats\Competition\Entity\Location');

        $filter = new LocationFilter($em, $repo);

        return $filter;
    }
}
