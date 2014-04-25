<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Application\Entity\AccountInterface;

/**
 * Match <-> Team Association Entity
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeam
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Match
     */
    protected $match;

    /**
     * @var Team
     */
    protected $team;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Collection
     */
    protected $players;

    /**
     * @var integer
     */
    protected $score;

    /**
     * @var Collection
     */
    protected $events;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function __clone()
    {
        $this->players = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

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
     * Team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set the team
     *
     * @param  Team $u
     * @return self
     */
    public function setTeam(Team $u = NULL)
    {
        $this->team = $u;

        return $this;
    }

    /**
     * Match
     *
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * Set the match
     *
     * @param  Match $m
     * @return self
     */
    public function setMatch(Match $m = NULL)
    {
        $this->match = $m;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if ( ! in_array($type, ['H','A']) ) {
            throw new \InvalidArgumentException('Type must be (H)ome or (A)way');
        }
        $this->type = $type;

        return $this;
    }

    public function isHomeTeam()
    {
        return $this->getType() == 'H';
    }

    public function isAwayTeam()
    {
        return $this->getType() == 'A';
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($score)
    {
        $this->score = (int) $score;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param  Collection $players
     * @return self
     */
    public function setPlayers(Collection $players)
    {
        $this->players->clear();
        $this->addPlayers($players);

        return $this;
    }

    /**
     * @param  Collection $players
     * @return self
     */
    public function addPlayers(Collection $players)
    {
        if (count($players)) {
            foreach ($players as $p) {
                $this->addPlayer($p);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamPlayer $role
     * @return self
     */
    public function addPlayer(MatchTeamPlayer $p)
    {
        if ( ! $this->hasPlayer($p) ) {
            $p->setTeam($this);
            $this->players->add($p);
        }

        return $this;
    }

    /**
     * @param  Collection $players
     * @return self
     */
    public function removePlayers(Collection $players)
    {
        if (count($players)) {
            foreach ($players as $p) {
                $this->removePlayer($p);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamPlayer $role
     * @return self
     */
    public function removePlayer(MatchTeamPlayer $p)
    {
        $p->setTeam(NULL);
        $this->players->removeElement($p);

        return $this;
    }

    /**
     * @param  MatchTeamPlayer|AccountInterface $role
     * @return bool
     */
    public function hasPlayer($p)
    {
        if ($p instanceof AccountInterface) {
            return $this->players->exists(function (MatchTeamPlayer $obj) use ($p) {
                return $obj->getPlayer()->getId() == $p->getId();
            });
        }

        return $this->players->contains($p);
    }

    /**
     * @return Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param  Collection $events
     * @return self
     */
    public function setEvents(Collection $events)
    {
        $this->events->clear();
        $this->addEvents($events);

        return $this;
    }

    /**
     * @param  Collection $events
     * @return self
     */
    public function addEvents(Collection $events)
    {
        if (count($events)) {
            foreach ($events as $p) {
                $this->addEvent($p);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamEvent $role
     * @return self
     */
    public function addEvent(MatchTeamEvent $p)
    {
        if ( ! $this->hasEvent($p) ) {
            $p->setMatch($this->getMatch());
            $p->setTeam($this);
            $this->events->add($p);
        }

        return $this;
    }

    /**
     * @param  Collection $events
     * @return self
     */
    public function removeEvents(Collection $events)
    {
        if (count($events)) {
            foreach ($events as $p) {
                $this->removeEvent($p);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamEvent $role
     * @return self
     */
    public function removeEvent(MatchTeamEvent $p)
    {
        $p->setMatch(NULL);
        $p->setTeam(NULL);
        $this->events->removeElement($p);

        return $this;
    }

    /**
     * @param  MatchTeamEvent $role
     * @return bool
     */
    public function hasEvent($p)
    {
        return $this->events->contains($p);
    }

    public function __toString()
    {
        return $this->getTeam()->getName();
    }
}
