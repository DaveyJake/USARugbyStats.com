<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEventHydrator;
use UsaRugbyStats\Competition\Hydrator\ObjectPopulateStrategy;

class CardEventFieldsetFactory implements FactoryInterface
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

        $form = new CardEventFieldset($om);

        // Set the hydrator
        $hydrator = new MatchTeamEventHydrator($om);
        $repo = $om->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $hydrator->addStrategy('player', new ObjectPopulateStrategy($repo));
        $form->setHydrator($hydrator);
        $form->setObject(new CardEvent());

        return $form;
    }
}
