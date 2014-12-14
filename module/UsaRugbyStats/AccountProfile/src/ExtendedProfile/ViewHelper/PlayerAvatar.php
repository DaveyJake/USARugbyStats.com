<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use LdcUserProfile\Service\ProfileService;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionEntity;

class PlayerAvatar extends AbstractHelper
{
    /**
     * @var \LdcUserProfile\Service\ProfileService
     */
    protected $service;

    public function __construct(ProfileService $svc)
    {
        $this->service = $svc;
    }

    public function __invoke(UserInterface $user, $settings = array())
    {
        $extprofile = $this->service->getExtensions()['extprofile']->getObjectForUser($user);
        if ( ! $extprofile instanceof ExtensionEntity || $extprofile->getPhotoSource() === 'G' ) {
            return $this->getView()->gravatar($user->getEmail(), $settings);
        }

        return sprintf(
            '<img src="%s" %s />',
            $this->getView()->ursPlayerPhotoUrl($user),
            $this->buildHtmlAttrs($settings)
        );
    }

    protected function buildHtmlAttrs($settings)
    {
        $set = [];
        foreach ($settings as $key => $value) {
            switch ($key) {
                case 'img_size':
                    $set[] = 'width="'.$this->getView()->escapeHtmlAttr($value).'"';
                    break;
                case 'class':
                    $set[] = 'class="'.$this->getView()->escapeHtmlAttr($value).'"';
                    break;
            }
        }

        return implode(' ', $set);
    }
}
