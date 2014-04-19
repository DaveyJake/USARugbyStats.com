<?php
namespace UsaRugbyStats\Competition\Form\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class MatchCreateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = new Form('create-competition-match');

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        // Set the base fieldset (competition)
        $matchFieldset = $sm->get('usarugbystats_competition_competition_match_fieldset');
        $matchFieldset->setUseAsBaseFieldset(true);
        $form->add($matchFieldset);

        // Construct the input filter
        $teamInputFilter = $sm->get('usarugbystats_competition_competition_match_inputfilter');

        $if = new InputFilter();
        $if->add($teamInputFilter, 'match');
        $form->setInputFilter($if);

        // Add the submit button
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Match',
            ),
        ));

        return $form;
    }
}
