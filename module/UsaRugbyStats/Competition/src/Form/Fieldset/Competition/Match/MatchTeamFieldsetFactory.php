<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollection;

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

        $collEvents = new NonuniformCollection();
        $collEvents->setName('events');
        $collEvents->setShouldCreateTemplate(true);
        $collEvents->setTemplatePlaceholder('__eventindex__');
        $collEvents->setTargetElement(array(
            'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\CardEvent' => $sm->get('usarugbystats_competition_competition_match_teamevent_cardfieldset'),
            'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\SubEvent' => $sm->get('usarugbystats_competition_competition_match_teamevent_subfieldset'),
            'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\ScoreEvent' => $sm->get('usarugbystats_competition_competition_match_teamevent_scorefieldset'),
        ));

        $form = new MatchTeamFieldset($om, $fsMatchTeamPlayer, $collEvents);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new MatchTeam());

        return $form;
    }
}
