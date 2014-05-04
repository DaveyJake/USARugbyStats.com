<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
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
        $form = new Form('create-team');

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        // Set the base fieldset (team)
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
