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
    const TYPE_BLOOD         = 'BL';
    const TYPE_INJURY        = 'IJ';
    const TYPE_FRONTROWCARD  = 'FRC';
    const TYPE_TACTICAL      = 'TC';

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
        if ( ! in_array($type, ['BL','IJ', 'FRC', 'TC'], true) ) {
            throw new \InvalidArgumentException('Signature type must be BL, IJ, FRC or TC');
        }
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
