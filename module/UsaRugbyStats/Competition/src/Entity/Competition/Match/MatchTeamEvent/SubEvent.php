<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;

/**
 * "Substitution" match event type
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class SubEvent extends MatchTeamEvent
{
    /**
     * Card Type
     *
     * @var string
     */
    protected $type;

    /**
     * @var MatchTeamPlayer
     */
    protected $playerOn;

    /**
     * @var MatchTeamPlayer
     */
    protected $playerOff;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getPlayerOn()
    {
        return $this->playerOn;
    }

    public function setPlayerOn(MatchTeamPlayer $playerOn)
    {
        $this->playerOn = $playerOn;

        return $this;
    }

    public function getPlayerOff()
    {
        return $this->playerOff;
    }

    public function setPlayerOff(MatchTeamPlayer $playerOff)
    {
        $this->playerOff = $playerOff;

        return $this;
    }

    public function getDiscriminator()
    {
        return 'sub';
    }

}
