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

    public function isTry() { return $this->getType() == 'TR'; }
    public function isConversion() { return $this->getType() == 'CV'; }
    public function isDropGoal() { return $this->getType() == 'DG'; }
    public function isPenaltyKick() { return $this->getType() == 'PK'; }
    public function isPenaltyTry() { return $this->getType() == 'PT'; }

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

    public function getPoints()
    {
        switch ( $this->getType() ) {
            case 'CV':
                return 2;
            case 'DG':
            case 'PK':
                return 3;
            case 'PT':
            case 'TR':
                return 5;
        }

        return 0;
    }
}
