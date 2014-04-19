<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $filter = new MatchFilter();

        return $filter;
    }
}
