<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;

/**
 * "Score" match event type
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class ScoreEvent extends MatchTeamEvent
{
    /**
     * Card Type
     *
     * @var string
     */
    protected $type;

    /**
     * Player who scored
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
        if ( ! in_array($type, ['CV','DG','PK','PT','TR'], true) ) {
            throw new \InvalidArgumentException('Signature type must be CV, DG, PK, PT or TR');
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
        return 'score';
    }
}
