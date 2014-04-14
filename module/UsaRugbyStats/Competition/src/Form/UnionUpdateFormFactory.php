<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class UnionUpdateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = $sm->get('usarugbystats_competition_union_createform');

        $form->setName('update-union');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
