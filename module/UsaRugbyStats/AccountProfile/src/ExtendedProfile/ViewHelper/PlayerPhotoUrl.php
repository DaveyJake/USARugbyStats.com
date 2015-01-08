<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Entity\UserInterface;

class PlayerPhotoUrl extends AbstractHelper
{
    protected $basePattern = '/assets/img/playeravatars/%s.png';

    public function __invoke($user, $settings = array())
    {
        if ($user instanceof UserInterface) {
            $userid = $user->getId();
        } elseif ( ctype_digit(trim($user)) ) {
            $userid = trim($user);
        }
        if ( empty($userid) ) {
            return sprintf($this->basePattern, 'notfound');
        }

        $url      = sprintf($this->basePattern, $userid);
        $filename = 'public' . $url;

        if ( ! file_exists($filename) ) {
            return sprintf($this->basePattern, 'notfound');
        }

        return $url;
    }
}
