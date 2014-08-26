<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchFieldsetFactory implements FactoryInterface
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

        $fsHomeTeam = $sm->get('usarugbystats_competition_competition_match_team_fieldset');
        $fsAwayTeam = $sm->get('usarugbystats_competition_competition_match_team_fieldset');
        $fsSignature = $sm->get('usarugbystats_competition_competition_match_signature_fieldset');

        $form = new MatchFieldset($om, $fsHomeTeam, $fsAwayTeam, $fsSignature);

        // Set the hydrator
        $form->setHydrator(new MatchFieldsetHydrator($om));
        $form->setObject(new Match());

        return $form;
    }
}
