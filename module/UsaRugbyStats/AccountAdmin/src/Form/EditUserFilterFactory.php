<?php
namespace UsaRugbyStats\AccountAdmin\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ZfcUser\Form\RegisterFilter;
use ZfcUserAdmin\Validator\NoRecordExistsEdit;
use Zend\Validator\Callback;
use UsaRugbyStats\Account\ZfcUser\UserMapper;
use UsaRugbyStats\Account\Entity\Account;
use Zend\Validator\NotEmpty;

class EditUserFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $zfcUserOptions = $sm->get('zfcuser_module_options');
        $zfcUserAdminOptions = $sm->get('zfcuseradmin_module_options');
        $zfcUserMapper = $sm->get('zfcuser_user_mapper');

        $emailValidator = new NotEmpty();
        $usernameValidator = new NoRecordExistsEdit(array(
            'mapper' => $zfcUserMapper,
            'key' => 'username'
        ));
        $filter = new RegisterFilter($emailValidator, $usernameValidator, $zfcUserOptions);

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

        if (!$zfcUserAdminOptions->getAllowPasswordChange()) {
            $filter->remove('password')->remove('passwordVerify');
        } else {
            $filter->get('password')->setRequired(false);
            $filter->remove('passwordVerify');
        }

        $filter->add(array(
            'name' => 'remoteId',
            'required' => false,
            'allow_empty' => true,
            'validators' => array(
                new Callback(function ($value, $context) use ($zfcUserMapper) {
                    if (! $zfcUserMapper instanceof UserMapper) {
                        return true;
                    }
                    $obj = $zfcUserMapper->findByRemoteId($value);

                    return ( ! $obj instanceof Account || $obj->getId() == $context['userId'] );
                }),
            ),
        ));

        $filter->add(array(
            'name'       => 'roleAssignments',
            'required'   => false,
            'allow_emtpy' => true,
        ));

        return $filter;
    }
}
