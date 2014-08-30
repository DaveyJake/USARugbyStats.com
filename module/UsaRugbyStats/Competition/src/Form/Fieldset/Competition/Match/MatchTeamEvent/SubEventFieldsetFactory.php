<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEventHydrator;
use UsaRugbyStats\Competition\Hydrator\ObjectPopulateStrategy;

class SubEventFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $om = $sm->get('zfcuser_doctrine_em');

        $form = new SubEventFieldset($om);

        // Set the hydrator
        $hydrator = new MatchTeamEventHydrator($om);
        $repo = $om->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $hydrator->addStrategy('playerOn', new ObjectPopulateStrategy($repo));
        $hydrator->addStrategy('playerOff', new ObjectPopulateStrategy($repo));
        $form->setHydrator($hydrator);
        $form->setObject(new SubEvent());

        return $form;
    }
}
