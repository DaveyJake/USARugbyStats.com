<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilter;

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
        $form = new TeamCreateForm('create-team');

        // Add the team fieldset
        $teamFieldset = $sm->get('usarugbystats_competition_team_fieldset');
        $teamFieldset->setUseAsBaseFieldset(true);
        $form->add($teamFieldset);

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
