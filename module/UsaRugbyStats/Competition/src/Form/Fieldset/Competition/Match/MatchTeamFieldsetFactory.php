<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;

class MatchTeamFieldsetFactory implements FactoryInterface
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

        $fsMatchTeamPlayer = $sm->get('usarugbystats_competition_competition_match_teamplayer_fieldset');
        $form = new MatchTeamFieldset($om, $fsMatchTeamPlayer);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new MatchTeam());

        return $form;
    }
}
