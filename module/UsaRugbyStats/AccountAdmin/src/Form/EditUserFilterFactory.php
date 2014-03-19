<?php
namespace UsaRugbyStats\AccountAdmin\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ZfcUser\Form\RegisterFilter;
use ZfcUserAdmin\Validator\NoRecordExistsEdit;

class EditUserFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $zfcUserOptions = $sm->get('zfcuser_module_options');
        $zfcUserAdminOptions = $sm->get('zfcuseradmin_module_options');
        
        $filter = new RegisterFilter(
            new NoRecordExistsEdit(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new NoRecordExistsEdit(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'username'
            )),
            $zfcUserOptions
        );
        if (!$zfcUserAdminOptions->getAllowPasswordChange()) {
            $filter->remove('password')->remove('passwordVerify');
        } else {
            $filter->get('password')->setRequired(false);
            $filter->remove('passwordVerify');
        }
        
        $filter->add(array(
            'name'       => 'roleAssignments',
            'required'   => false,
            'allow_emtpy' => true,
        ));
        
        return $filter;
    }
}