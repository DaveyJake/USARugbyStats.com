<?php
namespace UsaRugbyStats\Application\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

class IframeablePageListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $iframeablePages = array();
    protected $iframeTemplateName = 'layout/iframe';

    public function __construct(array $iframeablePages = array(), $iframeTemplateName)
    {
        $this->iframeablePages = $iframeablePages;
        $this->iframeTemplateName = $iframeTemplateName;
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

        if ( $e->getRequest()->getQuery('iframe') !== 'true' ) {
            return;
        }

        if ( ! in_array($e->getRouteMatch()->getMatchedRouteName(), $this->iframeablePages, true) ) {
            return;
        }

        $e->getViewModel()->setVariable('isIframe', true)
                          ->setTemplate($this->iframeTemplateName);
    }
}
