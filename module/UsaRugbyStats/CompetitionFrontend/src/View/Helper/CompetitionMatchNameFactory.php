<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompetitionMatchNameFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $viewHelper = new CompetitionMatchName();

        return $viewHelper;
    }
}
