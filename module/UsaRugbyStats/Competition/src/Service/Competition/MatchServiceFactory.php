<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('zfcuser_doctrine_em');

        $service = new MatchService();
        $service->setMatchObjectManager($em);
        $service->setMatchRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match'));
        $service->setCreateForm($sm->get('usarugbystats_competition_competition_match_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_competition_match_updateform'));

        // Populate service with the list of available "match event" types
        $fsMatchTeam = $sm->get('usarugbystats_competition_competition_match_team_fieldset');
        $classes = $fsMatchTeam->get('events')->getTargetElement();
        if ( is_array($classes) ) {
            $set = array();
            foreach ($classes as $item) {
                $set[$item->getName()] = array(
                    'name'              => $item->getName(),
                    'fieldset_class'    => get_class($item),
                    'entity_class'      => get_class($item->getObject()),
                );
            }
            $service->setAvailableMatchTeamEventTypes($set);
        }

        return $service;
    }
}
