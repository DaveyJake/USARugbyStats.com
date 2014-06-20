<?php
namespace UsaRugbyStats\Account\Traits;

use ZfcUser\Service\User as UserService;

trait UserServiceTrait
{
    /**
     * @var UserService
     */
    protected $userService;

    public function getUserService()
    {
        if ( is_null($this->userService) ) {
            if ( ! method_exists($this, 'getServiceLocator') ) {
                return NULL;
            }
            $this->userService = $this->getServiceLocator()->get(
                'zfcuser_user_service'
            );
        }

        return $this->userService;
    }

    public function setUserService(UserService $svc)
    {
        $this->userService = $svc;

        return $this;
    }
}
