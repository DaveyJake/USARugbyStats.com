<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use Doctrine\Common\Persistence\ObjectRepository;

class TeamPlayerLink extends AbstractHelper
{
    /**
     * @var ObjectRepository
     */
    protected $svc;

    public function __invoke($obj)
    {
        $player = null;
        if ($obj instanceof MatchTeamPlayer) {
            $player = $obj->getPlayer();
        } elseif ( ctype_digit(trim($obj)) ) {
            $player = $this->getMatchTeamPlayerRepository()->find($obj);
            if ( is_null($player) ) {
                return;
            }
            $player = $player->getPlayer();
        }
        if ( is_null($player) ) {
            return;
        }

        return $this->getView()->ursPlayerLink($player);
    }

    public function setMatchTeamPlayerRepository(ObjectRepository $svc)
    {
        $this->svc = $svc;

        return $this;
    }

    public function getMatchTeamPlayerRepository()
    {
        return $this->svc;
    }
}
