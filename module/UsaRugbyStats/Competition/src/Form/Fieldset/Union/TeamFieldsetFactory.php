<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Union;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Team;

class TeamFieldsetFactory implements FactoryInterface
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
        $mapper = $om->getRepository('UsaRugbyStats\Competition\Entity\Team');

        $fieldset = new TeamFieldset($om, $mapper);
        $fieldset->setHydrator(new DoctrineObject($om));
        $fieldset->setObject(new Team());

        return $fieldset;
    }
}
