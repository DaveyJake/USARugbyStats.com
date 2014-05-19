<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchTeamPlayerFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $em = $manager->get('zfcuser_doctrine_em');

        $filter = new MatchTeamPlayerFilter($em->getRepository('UsaRugbyStats\Account\Entity\Account'));

        return $filter;
    }
}
