<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class CompetitionCreateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $competitionFieldset = $sm->get('usarugbystats_competition_competition_fieldset');

        $form = new CompetitionCreateForm($competitionFieldset);

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        // Construct the input filter
        $teamInputFilter = $sm->get('usarugbystats_competition_competition_inputfilter');

        $if = new InputFilter();
        $if->add($teamInputFilter, 'competition');
        $form->setInputFilter($if);

        return $form;
    }
}
