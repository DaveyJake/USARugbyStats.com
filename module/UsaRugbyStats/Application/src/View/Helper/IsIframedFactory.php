<?php
namespace UsaRugbyStats\Application\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IsIframedFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $viewHelper = new IsIframed();

        return $viewHelper;
    }
}
