<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;

class MemberFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $fieldset = new MemberFieldset();
        $fieldset->setHydrator(new ObjectProperty());
        $fieldset->setObject(new \stdClass());

        return $fieldset;
    }
}
