<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator;

class SubEventFieldsetFactory implements FactoryInterface
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

        $form = new SubEventFieldset($om);

        // Set the hydrator
        $form->setHydrator(new NonuniformCollectionHydrator($om));
        $form->setObject(new SubEvent());

        return $form;
    }
}
