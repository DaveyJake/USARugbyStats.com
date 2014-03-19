<?php
namespace UsaRugbyStats\LegacyApplication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PageController extends AbstractActionController
{
    public function renderAction()
    {
        // We should only be handling routes for our own module
        $routeMatch = $this->getEvent()->getRouteMatch();
        if ( ! preg_match('{^urs-la/(.*)$}is', $routeMatch->getMatchedRouteName(), $parts) ) {
            throw new \InvalidArgumentException('This route is not eligible to use this controller');
        }

        // Fetch the configuration of the routed route 
        $config = $this->getServiceLocator()->get('Config');
        if ( !isset($config['router']['routes']['urs-la']['child_routes'][$parts[1]]) || 
             !isset($config['router']['routes']['urs-la']['child_routes'][$parts[1]]['options']['route']) )
        {
            throw new \InvalidArgumentException('The route you specified is not configured');
        }
        $routeConfig = $config['router']['routes']['urs-la']['child_routes'][$parts[1]];
        
        $routeSlug = $routeConfig['options']['route'];
        $requestSlug = ltrim($this->getRequest()->getServer('REDIRECT_URL'), '/');
        
        // The URI configured by the route doesn't match the URI from the request,
        // so we bail out as something is quite fishy here
        if ( $routeSlug != $requestSlug ) {
            throw new \InvalidArgumentException('This URI cannot be routed!');
        }

        $targetFile = $routeSlug;
        if ( isset($routeConfig['options']['target-file']) ) {
            $targetFile = $routeConfig['options']['target-file'];
        }
        
        // Locate the script file
        $scriptFile = realpath($config['usarugbystats']['legacy-application']['directory']
                        . DIRECTORY_SEPARATOR
                        . $targetFile);
        if ( empty($scriptFile) ) {
            throw new \RuntimeException('The script pointed to by this URI could not be found!');
        }
        chdir(dirname($scriptFile));
        
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/legacy-application/render');
        $vm->setVariables(array('scriptFile' => $scriptFile));
        if ( isset($routeConfig['options']['use-layout']) && $routeConfig['options']['use-layout'] == false ) {
            $vm->setTerminal(true);
        }
        return $vm;
    }
}