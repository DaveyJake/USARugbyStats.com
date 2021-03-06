<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

class TeamMembershipFieldsetFactory implements FactoryInterface
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

        $form = new TeamMembershipFieldset($om);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new TeamMembership());

        return $form;
    }
}
