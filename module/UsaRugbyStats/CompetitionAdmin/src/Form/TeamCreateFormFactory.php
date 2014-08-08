<?php
namespace UsaRugbyStats\CompetitionAdmin\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\InputFilter\CollectionInputFilter;
use UsaRugbyStats\Application\Common\ExtendedValidationGroupForm;

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
        $form = new ExtendedValidationGroupForm('create-team');

        $form->setHydrator(new ObjectProperty());
        $form->setObject(new \stdClass());

        // Add the team fieldset
        $teamFieldset = $sm->get('usarugbystats_competition_team_fieldset');
        $teamFieldset->setUseAsBaseFieldset(false);
        $form->add($teamFieldset);

        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Team',
            ),
        ));

        // Add the team administrator management element
        $form->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'administrators',
            'options' => array(
                'target_element' => $sm->get('usarugbystats_competition-admin_team_administrator_fieldset'),
                'should_create_template' => true,
                'count' => 0,
            )
        ));

        // Add the team membership management element
        $form->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'members',
            'options' => array(
                'target_element' => $sm->get('usarugbystats_competition-admin_team_member_fieldset'),
                'should_create_template' => true,
                'count' => 0,
            )
        ));

        // Construct the input filter

        $teamInputFilter = $sm->get('usarugbystats_competition_team_inputfilter');
        $ifAdministrator = $sm->get('usarugbystats_competition-admin_team_administrator_inputfilter');
        $ifMember = $sm->get('usarugbystats_competition-admin_team_member_inputfilter');

        $if = new InputFilter();
        $if->add($teamInputFilter, 'team');

        $cifAdministrators = new CollectionInputFilter();
        $cifAdministrators->setInputFilter($ifAdministrator);
        $cifAdministrators->setIsRequired(false);
        $if->add($cifAdministrators, 'administrators');

        $cifMembers = new CollectionInputFilter();
        $cifMembers->setInputFilter($ifMember);
        $cifMembers->setIsRequired(false);
        $if->add($cifMembers, 'members');

        $form->setInputFilter($if);

        return $form;
    }
}
