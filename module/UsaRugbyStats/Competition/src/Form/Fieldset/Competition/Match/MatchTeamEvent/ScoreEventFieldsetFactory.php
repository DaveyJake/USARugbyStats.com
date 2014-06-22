<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEventHydrator;

class ScoreEventFieldsetFactory implements FactoryInterface
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

        $form = new ScoreEventFieldset($om);

        // Set the hydrator
        $form->setHydrator(new MatchTeamEventHydrator($om));
        $form->setObject(new ScoreEvent());

        return $form;
    }
}
