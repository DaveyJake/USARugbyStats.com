<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class UnionCreateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = new UnionCreateForm('create-union');

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        // Set the base fieldset (union)
        $unionFieldset = $sm->get('usarugbystats_competition_union_fieldset');
        $unionFieldset->setUseAsBaseFieldset(true);
        $form->add($unionFieldset);

        // Construct the input filter
        $teamInputFilter = $sm->get('usarugbystats_competition_union_inputfilter');

        $if = new InputFilter();
        $if->add($teamInputFilter, 'union');
        $form->setInputFilter($if);

        // Add the submit button
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Union',
            ),
        ));

        return $form;
    }
}
