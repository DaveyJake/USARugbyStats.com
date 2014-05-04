<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition\Division;

class DivisionFieldsetFactory implements FactoryInterface
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
        $fsTeamMembership = $sm->get('usarugbystats_competition_competition_teammembership_fieldset');

        $form = new DivisionFieldset($om, $fsTeamMembership);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new Division());

        return $form;
    }
}
