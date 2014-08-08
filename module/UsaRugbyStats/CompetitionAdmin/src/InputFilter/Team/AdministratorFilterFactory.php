<?php
namespace UsaRugbyStats\CompetitionAdmin\InputFilter\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $om = $sm->get('zfcuser_doctrine_em');
        $mapper = $om->getRepository('UsaRugbyStats\Account\Entity\Account');

        $filter = new AdministratorFilter($om, $mapper);

        return $filter;
    }
}
