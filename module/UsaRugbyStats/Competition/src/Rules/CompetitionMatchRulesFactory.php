<?php
namespace UsaRugbyStats\Competition\Rules;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Application\FeatureFlags\FeatureFlags;
use UsaRugbyStats\Application\Rules\RulesEngine;

class CompetitionMatchRulesFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $engine = new RulesEngine();
        $engine->addToContext('flags', new FeatureFlags());

        // Load the rules from a ServiceManager config block
        $smConfig = @$sm->get('Config')['usarugbystats']['rules_engine']['competition_match'];
        if ( is_array($smConfig) ) {
            $smRules = new \Zend\ServiceManager\ServiceManager(
                new \Zend\ServiceManager\Config(
                    $smConfig
                )
            );
            $smRules->addPeeringServiceManager($sm);
            $engine->addRulesFromServiceManager($smRules);
        }

        return $engine;

    }
}
