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
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = new Form('create-competition');
        
        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));
        
        // Set the base fieldset (competition)
        $competitionFieldset = $sm->get('usarugbystats_competition_competition_fieldset');
        $competitionFieldset->setUseAsBaseFieldset(true);
        $form->add($competitionFieldset);
        
        // Construct the input filter
        $teamInputFilter = $sm->get('usarugbystats_competition_competition_inputfilter');
        
        $if = new InputFilter();
        $if->add($teamInputFilter, 'competition');
        $form->setInputFilter($if);

        // Add the submit button
        $form->add(array(
            'name' => 'submit',
        	'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Competition',
            ),
        ));
        
        return $form;
    }
}