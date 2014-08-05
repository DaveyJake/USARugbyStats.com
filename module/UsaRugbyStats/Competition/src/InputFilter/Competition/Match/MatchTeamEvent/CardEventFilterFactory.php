<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CardEventFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $manager)
    {
        $om = $manager->get('zfcuser_doctrine_em');
        $repo = $om->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');

        $filter = new CardEventFilter($repo);

        return $filter;
    }
}
