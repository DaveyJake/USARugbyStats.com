<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ObjectProperty;

class TeamCreateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = new Form('create-team');

        // Set the hydrator
        $form->setHydrator(new ObjectProperty());
        $form->setObject(new \stdClass());

        // Add the team fieldset
        $teamFieldset = $sm->get('usarugbystats_competition_team_fieldset');
        $teamFieldset->setUseAsBaseFieldset(false);
        $form->add($teamFieldset);

        // Add the team administrator management element
        $form->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'administrators',
            'options' => array(
                'target_element' => $sm->get('usarugbystats_competition_team_administrator_fieldset'),
                'should_create_template' => true,
                'count' => 0,
            )
        ));

        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Team',
            ),
        ));

        // Construct the input filter

        $teamInputFilter = $sm->get('usarugbystats_competition_team_inputfilter');

        $if = new InputFilter();
        $if->add($teamInputFilter, 'team');
        $form->setInputFilter($if);

        return $form;
    }
}
