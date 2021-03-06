<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use LdcZendFormCTI\Form\Element\NonuniformCollection;

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
        $collEvents->setDiscriminatorFieldName('event');
        $collEvents->setCount(0);
        $collEvents->setTargetElement(array(
            'card' => $sm->get('usarugbystats_competition_competition_match_teamevent_cardfieldset'),
            'sub' => $sm->get('usarugbystats_competition_competition_match_teamevent_subfieldset'),
            'score' => $sm->get('usarugbystats_competition_competition_match_teamevent_scorefieldset'),
        ));

        $form = new MatchTeamFieldset($om, $fsMatchTeamPlayer, $collEvents);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new MatchTeam());

        return $form;
    }
}
