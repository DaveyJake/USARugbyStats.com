<?php
namespace UsaRugbyStats\Account\ZfcUser;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Validator\NoRecordExists;
use ZfcUser\Form\Register;
use ZfcUser\Form\RegisterFilter;
use Zend\Validator\NotEmpty;

class UserRegisterFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $options = $sm->get('zfcuser_module_options');
        $form = new Register(null, $options);

        $emailValidator = new NotEmpty();
        $usernameValidator = new NoRecordExists(array(
            'mapper' => $sm->get('zfcuser_user_mapper'),
            'key'    => 'username'
        ));
        $filter = new RegisterFilter($emailValidator, $usernameValidator, $options);

        // Allow usernames to be 1-255 characters
        // (ZfcUser default is 3-255)
        $filter->remove('username');
        $filter->add(array(
            'name'       => 'username',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 1,
                        'max' => 255,
                    ),
                ),
                $usernameValidator,
            ),
        ));

        $form->setInputFilter($filter);

        return $form;
    }
}
