<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;

/**
 * "Card" match event type
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class CardEvent extends MatchTeamEvent
{
    /**
     * Card Type
     *
     * @var string
     */
    protected $type;

    /**
     * Player receiving card
     *
     * @var MatchTeamPlayer
     */
    protected $player;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if ( ! in_array($type, ['R','Y'], true) ) {
            throw new \InvalidArgumentException('Signature type must be (R)ed or (Y)ellow');
        }
        $this->type = $type;

        return $this;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function setPlayer(MatchTeamPlayer $player)
    {
        $this->player = $player;

        return $this;
    }

    public function getDiscriminator()
    {
        return 'card';
    }
}
