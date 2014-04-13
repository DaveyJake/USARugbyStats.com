<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $om = $sm->get('zfcuser_doctrine_em');
        $fsDivision = $sm->get('usarugbystats_competition_competition_division_fieldset');
        
        $form = new CompetitionFieldset($om, $fsDivision);
        
        // Set the hydrator
        $form->setHydrator(new DoctrineObject($om));
        $form->setObject(new Competition());
        
        $form->get('divisions')->setHydrator($form->getHydrator());
        
        return $form;
    }
}