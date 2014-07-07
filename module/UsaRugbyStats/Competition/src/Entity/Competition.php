<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

/**
 * Competition
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Competition
{
    const VARIANT_SEVENS = '7s';
    const VARIANT_FIFTEENS = '15s';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $variant;

    /**
     * Divisions in this competition
     *
     * @var Collection
     */
    protected $divisions;

    /**
     * Matches which make up the competition
     *
     * @var Collection
     */
    protected $matches;

    /**
     * Team Memberships
     *
     * @var Collection
     */
    protected $teamMemberships;

    public function __construct()
    {
        $this->divisions = new ArrayCollection();
        $this->teamMemberships = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }

    /**
     * Competition Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Competition Identifier
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
     * Competition Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Competition Name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Competition Start Date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set Competition Start Date
     *
     * @param  \DateTime $startDate
     * @return self
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Competition End Date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set Competition End Date
     *
     * @param  \DateTime $endDate
     * @return self
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Rugby Variant (7s, 15s)
     *
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Specify Rugby Variant being played
     *
     * @param  string                    $variant
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setVariant($variant)
    {
        if ( ! in_array($variant, ['7s', '15s']) ) {
            throw new \InvalidARgumentException('Invalid variant');
        }
        $this->variant = $variant;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDivisions()
    {
        return $this->divisions;
    }

    /**
     * @param  Collection $divisions
     * @return self
     */
    public function setDivisions(Collection $divisions)
    {
        $this->divisions->clear();
        $this->addDivisions($divisions);

        return $this;
    }

    /**
     * @param  Collection $divisions
     * @return self
     */
    public function addDivisions(Collection $divisions)
    {
        if (count($divisions)) {
            foreach ($divisions as $ra) {
                $this->addDivision($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Competition\Division $role
     * @return self
     */
    public function addDivision(Competition\Division $ra)
    {
        if ( ! $this->hasDivision($ra) ) {
            $ra->setCompetition($this);
            $this->divisions->add($ra);
        }

        return $this;
    }

    /**
     * @param  Collection $divisions
     * @return self
     */
    public function removeDivisions(Collection $divisions)
    {
        if (count($divisions)) {
            foreach ($divisions as $ra) {
                $this->removeDivision($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Competition\Division $role
     * @return self
     */
    public function removeDivision(Competition\Division $ra)
    {
        $ra->setCompetition(null);
        $this->divisions->removeElement($ra);

        return $this;
    }

    /**
     * @param  Competition\Division $role
     * @return bool
     */
    public function hasDivision(Competition\Division $ra)
    {
        return $this->divisions->contains($ra);
    }

    /**
     * @return Collection
     */
    public function getTeamMemberships()
    {
        return $this->teamMemberships;
    }

    /**
     * @param  Collection $teamMemberships
     * @return self
     */
    public function setTeamMemberships(Collection $teamMemberships)
    {
        $this->teamMemberships->clear();
        $this->addTeamMemberships($teamMemberships);

        return $this;
    }

    /**
     * @param  Collection $teamMemberships
     * @return self
     */
    public function addTeamMemberships(Collection $teamMemberships)
    {
        if (count($teamMemberships)) {
            foreach ($teamMemberships as $ra) {
                $this->addTeamMembership($ra);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMembership $obj
     * @return self
     */
    public function addTeamMembership(TeamMembership $obj)
    {
        if ( ! $this->hasTeamMembership($obj) ) {
            $obj->setCompetition($this);
            $this->teamMemberships->add($obj);
        }

        return $this;
    }

    /**
     * @param  Collection $teamMemberships
     * @return self
     */
    public function removeTeamMemberships(Collection $teamMemberships)
    {
        if (count($teamMemberships)) {
            foreach ($teamMemberships as $ra) {
                $this->removeTeamMembership($ra);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMembership $obj
     * @return self
     */
    public function removeTeamMembership(TeamMembership $obj)
    {
        $obj->setCompetition(null);
        $this->teamMemberships->removeElement($obj);

        return $this;
    }

    /**
     * @param  TeamMembership $obj
     * @return bool
     */
    public function hasTeamMembership(TeamMembership $obj)
    {
        return $this->teamMemberships->contains($obj);
    }

    /**
     * Determine if the given team is competing in this competition
     *
     * @param  Team    $t
     * @return boolean
     */
    public function hasTeam(Team $t)
    {
        $result = $this->teamMemberships->filter(function ($i) use ($t) {
            return is_null($i->getTeam()->getId())
                ? $i->getTeam() === $t
                : $i->getTeam()->getId() === $t->getId();
        });

        return $result->count() > 0;
    }

    /**
     * @return Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @param  Collection $matches
     * @return self
     */
    public function setMatches(Collection $matches)
    {
        $this->matches->clear();
        $this->addMatches($matches);

        return $this;
    }

    /**
     * @param  Collection $matches
     * @return self
     */
    public function addMatches(Collection $matches)
    {
        if (count($matches)) {
            foreach ($matches as $ra) {
                $this->addMatch($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Competition\Match $role
     * @return self
     */
    public function addMatch(Competition\Match $ra)
    {
        if ( ! $this->hasMatch($ra) ) {
            $ra->setCompetition($this);
            $this->matches->add($ra);
        }

        return $this;
    }

    /**
     * @param  Collection $matches
     * @return self
     */
    public function removeMatches(Collection $matches)
    {
        if (count($matches)) {
            foreach ($matches as $ra) {
                $this->removeMatch($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Competition\Match $role
     * @return self
     */
    public function removeMatch(Competition\Match $ra)
    {
        $ra->setCompetition(null);
        $this->matches->removeElement($ra);

        return $this;
    }

    /**
     * @param  Competition\Match $role
     * @return bool
     */
    public function hasMatch(Competition\Match $ra)
    {
        return $this->matches->contains($ra);
    }

    public function getPointStructure()
    {
        $points = [
            'win'          => 4,
            'loss'         => 0,
            'tie'          => 2,
            'forfeit'      => -1,
            'try_bonus'    => 1,
            'loss_bonus'   => 1,
        ];

        if ( $this->getVariant() == '7s') {
            $points['win'] = 3;
            $points['loss'] = 1;
            $points['try_bonus'] = 0;
            $points['loss_bonus'] = 0;
        }

        return $points;
    }

    /**
     * String representation of this Competition object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

}
