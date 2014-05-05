<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

/**
 * Competition Match
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Match
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * The competition this match is part of
     *
     * @var Competition
     */
    protected $competition;

    /**
     * The date and time of the match
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Home Team
     *
     * @var MatchTeam
     */
    protected $homeTeam;

    /**
     * Away Team
     *
     * @var MatchTeam
     */
    protected $awayTeam;

    protected $teams;

    /**
     * Match Events
     *
     * @var Collection
     */
    protected $events;

    /**
     * Status of Match
     *
     * @var string
     */
    protected $status = 'NS';

    /**
     * Match Signatures
     *
     * @var Collection
     */
    protected $signatures;

    public function __construct()
    {
        $this->signatures = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function __clone()
    {
        $this->signatures = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->teams = new ArrayCollection();
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
     * Match Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Match Description
     *
     * @param  string $desc
     * @return self
     */
    public function setDescription($desc)
    {
        $this->description = $desc;

        return $this;
    }

    /**
     * Competition this team is a member of
     *
     * @return Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set the competition this team is a member of
     *
     * @param  Competition $u
     * @return self
     */
    public function setCompetition(Competition $u = NULL)
    {
        $this->competition = $u;

        return $this;
    }

    /**
     * Home team for this match
     *
     * @return MatchTeam
     */
    public function getHomeTeam()
    {
        return $this->getTeam('H');
    }

    /**
     * Set the home team for this match
     *
     * @param  MatchTeam $u
     * @return self
     */
    public function setHomeTeam(MatchTeam $u)
    {
        $u->setType('H');
        $this->addTeam($u);

        return $this;
    }

    /**
     * Away team for this match
     *
     * @return MatchTeam
     */
    public function getAwayTeam()
    {
        return $this->getTeam('A');
    }

    /**
     * Set the away team for this match
     *
     * @param  MatchTeam $u
     * @return self
     */
    public function setAwayTeam(MatchTeam $u)
    {
        $u->setType('A');
        $this->addTeam($u);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param  string         $type
     * @return MatchTeam|null
     */
    public function getTeam($type)
    {
        if ( ! $this->teams->containsKey($type) ) {
            $obj = new MatchTeam();
            $obj->setType($type);
            $this->addTeam($obj);
        }

        return $this->teams->get($type);
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function setTeams(Collection $teams)
    {
        $this->teams->clear();
        $this->addTeams($teams);

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function addTeams(Collection $teams)
    {
        if (count($teams)) {
            foreach ($teams as $ra) {
                $this->addTeam($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Team $obj
     * @return self
     */
    public function addTeam(MatchTeam $obj)
    {
        $obj->setMatch($this);
        $this->teams->set($obj->getType(), $obj);

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function removeTeams(Collection $teams)
    {
        if (count($teams)) {
            foreach ($teams as $ra) {
                $this->removeTeam($ra);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeam $obj
     * @return self
     */
    public function removeTeam(MatchTeam $obj)
    {
        $obj->setMatch(NULL);
        $this->teams->remove($obj->getType());

        return $this;
    }

    /**
     * @param  MatchTeam $obj
     * @return bool
     */
    public function hasTeam(MatchTeam $obj)
    {
        return $this->teams->containsKey($obj->getType())
            || $this->teams->contains($obj);
    }

    /**
     * DateTime of Match
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set DateTime of Match
     *
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Status of Match
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status of Match
     *
     * @param  string $status
     * @return self
     */
    public function setStatus($status)
    {
        if ( ! in_array($status, ['NS','S','F','HF','AF','C']) ) {
            throw new \InvalidArgumentException('Invalid match status!');
        }
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSignatures()
    {
        return $this->signatures;
    }

    /**
     * @param  Collection $signatures
     * @return self
     */
    public function setSignatures(Collection $signatures)
    {
        $this->signatures->clear();
        $this->addSignatures($signatures);

        return $this;
    }

    /**
     * @param  Collection $signatures
     * @return self
     */
    public function addSignatures(Collection $signatures)
    {
        if (count($signatures)) {
            foreach ($signatures as $ra) {
                $this->addSignature($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Signature $obj
     * @return self
     */
    public function addSignature(MatchSignature $obj)
    {
        if ( ! $this->hasSignature($obj) ) {
            $obj->setMatch($this);
            $this->signatures->add($obj);
        }

        return $this;
    }

    /**
     * @param  Collection $signatures
     * @return self
     */
    public function removeSignatures(Collection $signatures)
    {
        if (count($signatures)) {
            foreach ($signatures as $ra) {
                $this->removeSignature($ra);
            }
        }

        return $this;
    }

    /**
     * @param  MatchSignature $obj
     * @return self
     */
    public function removeSignature(MatchSignature $obj)
    {
        $obj->setMatch(NULL);
        $this->signatures->removeElement($obj);

        return $this;
    }

    /**
     * @param  MatchSignature $obj
     * @return bool
     */
    public function hasSignature(MatchSignature $obj)
    {
        return $this->signatures->contains($obj);
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
            foreach ($events as $ra) {
                $this->addEvent($ra);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamEvent $role
     * @return self
     */
    public function addEvent(MatchTeamEvent $ra)
    {
        if ( ! $this->hasEvent($ra) ) {
            $ra->setMatch($this);
            $this->events->add($ra);
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
            foreach ($events as $ra) {
                $this->removeEvent($ra);
            }
        }

        return $this;
    }

    /**
     * @param  MatchTeamEvent $role
     * @return self
     */
    public function removeEvent(MatchTeamEvent $ra)
    {
        $ra->setMatch(NULL);
        $this->events->removeElement($ra);

        return $this;
    }

    /**
     * @param  Event $role
     * @return bool
     */
    public function hasEvent(MatchTeamEvent $ra)
    {
        return $this->events->contains($ra);
    }

    /**
     * String representation of this Match object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHomeTeam()->getTeam()->getName() . ' v. ' . $this->getAwayTeam()->getTeam()->getName();
    }

}