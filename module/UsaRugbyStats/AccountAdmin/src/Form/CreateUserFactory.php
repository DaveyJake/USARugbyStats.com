<?php
namespace UsaRugbyStats\AccountAdmin\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ZfcUser\Form\RegisterFilter;
use ZfcUser\Validator\NoRecordExists;

/**
 * Factory for creating instance of CreateUser form
 * @TODO this should be upstreamed to ZfcUserAdmin w/ new configuration option for configuring form class name 
 * 
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class CreateUserFactory implements FactoryInterface
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
        
        $form = new CreateUser(null, $zfcUserAdminOptions, $zfcUserOptions, $sm);
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));
        
        $filter = new RegisterFilter(
            new NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'email'
            )),
            new NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key' => 'username'
            )),
            $zfcUserOptions
        );
        if ($zfcUserAdminOptions->getCreateUserAutoPassword()) {
            $filter->remove('password')->remove('passwordVerify');
        }
        $form->setInputFilter($filter);
        return $form;
    }
}