<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Application\Service\ServiceExtensionManager;
use UsaRugbyStats\Application\Service\ServiceExtensionManagerConfig;

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
        $service->setObjectManager($em);
        $service->setRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match'));
        $service->setCreateForm($sm->get('usarugbystats_competition_competition_match_createform'));
        $service->setUpdateForm($sm->get('usarugbystats_competition_competition_match_updateform'));

        //@TODO this should probably be in an initializer or abstract factory
        $config = @$sm->get('Config')['usarugbystats']['service_extensions']['usarugbystats_competition_competition_match_service'];
        if ( is_array($config) ) {
            $extmgr = new ServiceExtensionManager(new ServiceExtensionManagerConfig($config));
            $extmgr->addPeeringServiceManager($sm);
            $service->setExtensionManager($extmgr);
        }

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
