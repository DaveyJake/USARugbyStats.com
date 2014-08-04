<?php
namespace UsaRugbyStats\Application\Service;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceManager;

class ServiceExtensionManagerConfig implements ConfigInterface
{
    /**
     * @var array
     */
    protected $configuration = array();

    protected $defaultConfig = array(
        'extension_manager' => array(),
        'event_map' => array(),
    );

    /**
     * @var array
    */
    protected $services = array();

    /**
     * @param null|array $aConfiguration
    */
    public function __construct(array $aConfiguration = null)
    {
        if (is_array($aConfiguration)) {
            $this->setConfiguration($aConfiguration);
        } else {
            $this->configuration = $this->defaultConfig;
        }
    }

    /**
     * Configure service manager
     *
     * @param  ServiceManager $serviceManager
     * @return void
     */
    public function configureServiceManager(ServiceManager $serviceManager)
    {
        $serviceManager->setService('configuration', $this->configuration);
        $serviceManager->setAllowOverride(true);

        if (count($this->services)) {
            foreach ($this->services as $alias => $service) {
                $serviceManager->setService($alias, $service);
            }
        }

        $serviceBusManagerServicesConfig = new Config($this->configuration['extension_manager']);
        $serviceBusManagerServicesConfig->configureServiceManager($serviceManager);

        $serviceManager->setEventMap($this->configuration['event_map']);

        $serviceManager->setAllowOverride(false);
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $configuration = array_merge_recursive($this->defaultConfig, $configuration);

        $this->configuration = $configuration;
    }

}
