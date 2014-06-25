<?php
namespace UsaRugbyStats\Application\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IframeablePageListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('Config');
        $iframeablePages = isset($config['usarugbystats']['application']['iframeable']['routes'])
            ? $config['usarugbystats']['application']['iframeable']['routes']
            : array();
        $iframeLayout = isset($config['usarugbystats']['application']['iframeable']['layout'])
            ? $config['usarugbystats']['application']['iframeable']['layout']
            : NULL;

        return new IframeablePageListener($iframeablePages, $iframeLayout);
    }
}
