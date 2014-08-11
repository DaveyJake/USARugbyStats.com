<?php
namespace UsaRugbyStats\Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

class IsIframed extends AbstractHelper
{
    protected $sessionContainer;

    public function __invoke()
    {
        return $this->getSessionContainer()->flag === true;
    }

    public function getSessionContainer()
    {
        if ( empty($this->sessionContainer) ) {
            $this->sessionContainer = new Container('usarugbystats_iframeable');
        }
        return $this->sessionContainer;
    }
}
