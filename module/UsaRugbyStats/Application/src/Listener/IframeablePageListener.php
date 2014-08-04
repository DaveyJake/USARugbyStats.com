<?php
namespace UsaRugbyStats\Application\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class IframeablePageListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $iframeablePages = array();
    protected $iframeTemplateName = 'layout/iframe';
    protected $sessionContainer;

    public function __construct(array $iframeablePages = array(), $iframeTemplateName, Container $c = null)
    {
        $this->iframeablePages = $iframeablePages;
        $this->iframeTemplateName = $iframeTemplateName;
        $this->sessionContainer = $c ?: new Container('usarugbystats_iframeable');
    }

    public function attach(EventManagerInterface $events)
    {
        if ( empty($this->iframeablePages) ) {
            return;
        }
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'makeIframeable'), 100);
    }

    public function makeIframeable(EventInterface $e)
    {
        if (! $e instanceof MvcEvent) {
            throw new \RuntimeException('IframeablePageListener must be attached to MvcEvent::EVENT_RENDER');
        }
        if ( $e->getRequest() instanceof \Zend\Console\Request ) {
            return;
        }

        if ( $e->getRequest()->getQuery('iframe') !== NULL ) {
            switch ( $e->getRequest()->getQuery('iframe') ) {
                case 'true':
                    $this->sessionContainer->flag = true;
                    break;
                case 'false':
                    $this->sessionContainer->flag = false;
                    break;
            }
        }

        if ( ! isset($this->sessionContainer->flag) || $this->sessionContainer->flag === false ) {
            return;
        }

        if ( ! in_array('*', $this->iframeablePages, true) && ! in_array($e->getRouteMatch()->getMatchedRouteName(), $this->iframeablePages, true) ) {
            return;
        }

        $e->getViewModel()->setVariable('isIframe', true)
                          ->setTemplate($this->iframeTemplateName);
    }
}
