<?php
namespace UsaRugbyStats\AccountProfile\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Entity\UserInterface;
use LdcUserProfile\Service\ProfileService;

class PlayerProfileHelper extends AbstractHelper
{
    /**
     * @var ProfileService
     */
    protected $service;
    
    public function __construct(ProfileService $svc)
    {
        $this->service = $svc;
    }
    
    public function __invoke(UserInterface $player)
    {
        $obj = $this->service->getProfileForUser($player);
        return $obj ?: ($this->service->getFormPrototype()->getObject());
    }
}
