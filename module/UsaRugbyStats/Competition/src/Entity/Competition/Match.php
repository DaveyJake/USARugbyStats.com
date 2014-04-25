<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;

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
    }

    public function __clone()
    {
        $this->signatures = new ArrayCollection();
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
        return $this->homeTeam;
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
        $u->setMatch($this);

        $this->homeTeam = $u;

        return $this;
    }

    /**
     * Away team for this match
     *
     * @return MatchTeam
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
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
        $u->setMatch($this);

        $this->awayTeam = $u;

        return $this;
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
        $obj->setMatch($this);
        $this->signatures->set($obj->getType(), $obj);

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
        return $this->signatures->contains($obj)
            || $this->signatures->containsKey($obj->getType());
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
