<?php
namespace UsaRugbyStats\Account\Controller\Plugin;

use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class UserPlugin extends ZfcUserAuthentication
{
    /**
     * __invoke
     *
     * @access public
     * @return \ZfcUser\Entity\UserInterface
     */
    public function __invoke()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->getAuthService()->getIdentity();
        } else {
            return false;
        }
    }    
}