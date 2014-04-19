<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use UsaRugbyStats\Application\Entity\AccountInterface;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
/**
 * Match <-> Player Association Entity (via MatchTeam)
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamPlayer
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var MatchTeam
     */
    protected $team;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var AccountInterface
     */
    protected $player;

    /**
     * @var string
     */
    protected $position;

    //@TODO events

    /**
     * @var bool
     */
    protected $isFrontRow;

    /**
     * Match Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Match Identifier
     *
     * @param  integer $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Team this membership applies to
     *
     * @return MatchTeam
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set the team this membership applies to
     *
     * @param  MatchTeam $u
     * @return self
     */
    public function setTeam(MatchTeam $u = NULL)
    {
        $this->team = $u;

        return $this;
    }

    /**
     * Get Player's Account
     *
     * @return AccountInterface
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set Player's Account
     *
     * @param  AccountInterface $player
     * @return self
     */
    public function setPlayer(AccountInterface $player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get Jersey Number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set Jersey Number of Player
     *
     * @param  string $number
     * @return self
     */
    public function setNumber($number)
    {
        if ( ! ( is_numeric($number) && $number >= 0 && $number <= 99 ) ) {
            throw new InvalidArgumentException('Invalid jersey number!');
        }

        $this->number = $number;

        return $this;
    }

    /**
     * Get Player's Position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set Player's Position
     *
     * @param  string $position
     * @return self
     */
    public function setPosition($position)
    {
        if ( ! in_array($position, ['LHP','H','THP','L1','L2','OSF','BSF','N8','SH','FH','IC','OC','W1','W2','FB','R1','R2','R3','R4','R5','R6','R7','R8']) ) {
            throw new InvalidArgumentException('Invalid position type!');
        }
        $this->position = $position;

        return $this;
    }

    /**
     * Is Player Front-Row?
     *
     * @return boolean
     */
    public function getIsFrontRow()
    {
        return $this->isFrontRow;
    }

    /**
     * Is Player Front-Row?
     *
     * @return boolean
     */
    public function isFrontRow()
    {
        return $this->getIsFrontRow() == true;
    }

    /**
     * Set "Is Player Front-Row?" flag
     * @param  bool $tf
     * @return self
     */
    public function setIsFrontRow($tf)
    {
        $this->isFrontRow = ($tf == true);

        return $this;
    }

}
