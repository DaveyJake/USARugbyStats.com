<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;
use ZfcUser\Service\User;

class PlayerName extends AbstractHelper
{
    protected $accountService;

    public function __invoke($obj)
    {
        $player = null;
        if ($obj instanceof AccountInterface) {
            $player = $obj;
        } elseif ($obj instanceof MatchTeamPlayer) {
            $player = $obj->getPlayer();
        } elseif ( ctype_digit(trim($obj)) ) {
            $player = $this->getAccountService()->getUserMapper()->findById($obj);
        }
        if ( is_null($player) ) {
            return;
        }

        return $player->getDisplayName();
    }

    public function setAccountService(User $svc)
    {
        $this->accountService = $svc;

        return $this;
    }

    public function getAccountService()
    {
        return $this->accountService;
    }
}
