<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;
use ZfcUser\Service\User;

class PlayerLink extends AbstractHelper
{
    /**
     * @var \ZfcUser\Service\User
     */
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

        return $this->getView()->render(
            'usa-rugby-stats/competition-frontend/partials/player-link/default',
            [ 'player' => $player ]
        );
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
