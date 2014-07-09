<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;

class AdministratorFieldsetFactory implements FactoryInterface
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

        $fieldset = new AdministratorFieldset($om, $mapper);
        $fieldset->setHydrator(new ObjectProperty());
        $fieldset->setObject(new \stdClass());

        return $fieldset;
    }
}
