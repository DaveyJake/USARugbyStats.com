<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Entity\Location;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Application\Entity\AccountInterface;

/**
 * Competition Match
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Match
{
    const STATUS_NOTSTARTED = 'NS';
    const STATUS_STARTED = 'S';
    const STATUS_FINISHED = 'F';
    const STATUS_HOMEFORFEIT = 'HF';
    const STATUS_AWAYFORFEIT = 'AF';
    const STATUS_CANCELLED = 'C';

    /**
     * @var integer
     */
    protected $id;

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
     * Is stored DateTime instance localized?
     *
     * @var unknown
     */
    protected $dateLocalized = false;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * Location (venue) for the match
     *
     * @var Location|null
     */
    protected $location;

    /**
     * Extra location-related detail for this match
     *
     * @var string
     */
    protected $locationDetails = '';

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
     * Is Match Locked?
     *
     * @var bool
     */
    protected $isLocked = false;

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
    public function setCompetition(Competition $u = null)
    {
        $this->competition = $u;

        return $this;
    }

    /**
     * Location/venue of the match
     *
     * @return Location|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the location/venue for this match
     *
     * @param  Location $obj
     * @return self
     */
    public function setLocation(Location $obj = null)
    {
        $this->location = $obj;

        return $this;
    }

    /**
     * Extra location-related details for this match
     *
     * @return string
     */
    public function getLocationDetails()
    {
        return $this->locationDetails;
    }

    /**
     * Set extra location related-details
     *
     * @param  string $obj
     * @return self
     */
    public function setLocationDetails($text)
    {
        $this->locationDetails = $text;

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
        $obj->setMatch(null);
        $this->teams->remove($obj->getType());

        return $this;
    }

    /**
     * @param  MatchTeam|string $obj
     * @return bool
     */
    public function hasTeam($obj)
    {
        return $obj instanceof MatchTeam
            ? ( $this->teams->containsKey($obj->getType()) || $this->teams->contains($obj) )
            : $this->teams->containsKey($obj);
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        if (!$this->dateLocalized && !empty($this->timezone)) {
            $this->date->setTimezone(
                new \DateTimeZone($this->timezone)
            );
        }

        return $this->date;
    }

    /**
     * @param DateTime $dt
     */
    public function setDate(\DateTime $dt)
    {
        $this->dateLocalized = false;
        $this->date = $dt;

        return $this;
    }

    /**
     * @return the $timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param string $tz
     */
    public function setTimezone($tz)
    {
        $this->timezone = $tz;

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

    public function isNotStarted() { return $this->getStatus() == 'NS'; }
    public function isStarted() { return $this->getStatus() == 'S'; }
    public function isFinished() { return $this->getStatus() == 'F'; }
    public function isHomeForfeit() { return $this->getStatus() == 'HF'; }
    public function isAwayForfeit() { return $this->getStatus() == 'AF'; }
    public function isCancelled() { return $this->getStatus() == 'C'; }

    public function isComplete() { return in_array($this->getStatus(), ['F','HF','AF']); }

    public function getWinningSide()
    {
        if ( $this->isNotStarted() || $this->isCancelled() ) {
            return NULL;
        }

        $hs = $this->getHomeTeam()->getScore();
        $as = $this->getAwayTeam()->getScore();
        if ($this->isAwayForfeit() || $hs > $as) {
            return 'H';
        }
        if ($this->isHomeForfeit() || $hs < $as) {
            return 'A';
        }

        return 'D';
    }

    /**
     * Is Match Locked?
     *
     * @return boolean
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * Is Match Locked?
     *
     * @return boolean
     */
    public function isLocked()
    {
        return $this->getIsLocked() == true;
    }

    /**
     * Set "Match Locked" flag
     *
     * @param  bool $tf
     * @return self
     */
    public function setIsLocked($tf)
    {
        $this->isLocked = ($tf == true);

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
        $obj->setMatch(null);
        $this->signatures->removeElement($obj);

        return $this;
    }

    /**
     * @param  MatchSignature|string $obj
     * @return bool
     */
    public function hasSignature($obj)
    {
        if ($obj instanceof MatchSignature) {
            return $this->signatures->contains($obj);
        }

        return $this->signatures->filter(function (MatchSignature $item) use ($obj) {
            return $item->getType() == $obj;
        })->count() > 0;
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

        // Keep the MatchTeamEvent collections in
        // both Match and MatchTeam in sync
        if ( $this->getHomeTeam() ) {
            $this->getHomeTeam()->getEvents()->clear();
        }
        if ( $this->getAwayTeam() ) {
            $this->getAwayTeam()->getEvents()->clear();
        }

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
        if ( $this->hasEvent($ra) ) {
            return $this;
        }

        $ra->setMatch($this);
        $this->events->add($ra);

        // Keep the MatchTeamEvent collections in
        // both Match and MatchTeam in sync
        if ( $ra->getTeam() ) {
            $ra->getTeam()->addEvent($ra);
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
        if ( ! $this->hasEvent($ra) ) {
            return $this;
        }

        $ra->setMatch(null);
        $this->events->removeElement($ra);

        // Keep the MatchTeamEvent collections in
        // both Match and MatchTeam in sync
        if ( $ra->getTeam() ) {
            $ra->getTeam()->removeEvent($ra);
        }

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

    public function getRosterPositionForPlayer(AccountInterface $p)
    {
        foreach (['H', 'A'] as $side) {
            if ( ! $this->getTeam($side) instanceof MatchTeam ) {
                continue;
            }
            $result = $this->getTeam($side)->getPlayers()->filter(function ($slot) use ($p) {
                return $slot->getPlayer() && $slot->getPlayer()->getId() === $p->getId();
            });
            if (!empty($result)) {
                return $result->first();
            }
        }

        return false;
    }

    public function recalculateScore()
    {
        $score = ['H' => 0, 'A' => 0];
        foreach ( $this->getEvents() as $event ) {
            if ( ! $event->getTeam() instanceof MatchTeam ) {
                continue;
            }
            if ( $event->getDiscriminator() === 'score' ) {
                $score[$event->getTeam()->getType()] += $event->getPoints();
            }
            $event->setRunningScore($score);
        }
        if ($this->getHomeTeam() instanceof MatchTeam ) {
            $this->getHomeTeam()->setScore($score['H']);
        }
        if ($this->getAwayTeam() instanceof MatchTeam ) {
            $this->getAwayTeam()->setScore($score['A']);
        }

        return $this;
    }

    /**
     * String representation of this Match object
     *
     * @return string
     */
    public function __toString()
    {
        $homeTeam = "No Team Selected";
        if ( $this->getHomeTeam() instanceof MatchTeam && $this->getHomeTeam()->getTeam() instanceof Team ) {
            $homeTeam = $this->getHomeTeam()->getTeam()->getName();
        }
        $awayTeam = "No Team Selected";
        if ( $this->getAwayTeam() instanceof MatchTeam && $this->getAwayTeam()->getTeam() instanceof Team ) {
            $awayTeam = $this->getAwayTeam()->getTeam()->getName();
        }

        return $homeTeam . ' v. ' . $awayTeam;
    }

}
