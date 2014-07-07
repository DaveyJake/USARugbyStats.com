<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\Match;

/**
 * Locations
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Location
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var string
     */
    protected $coordinates = '';

    /**
     * Location Memberships
     *
     * @var Collection
     */
    protected $matches;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
    }

    public function __clone()
    {
        $this->matches = new ArrayCollection();
    }

    /**
     * Location Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Location Identifier
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
     * Location Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Location Name
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
     * Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set Address
     *
     * @param  string $addr
     * @return self
     */
    public function setAddress($addr)
    {
        $this->address = $addr;

        return $this;
    }

    /**
     * Map Coordinates of Location
     *
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set Coordinates
     *
     * @param  string $latlon
     * @return self
     */
    public function setCoordinates($latlon)
    {
        if ( !empty($latlon) && ! preg_match('{^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$}is', $latlon) ) {
            throw new \InvalidArgumentException('Invalid latitude/longitude!');
        }

        $this->coordinates = $latlon;

        return $this;
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
            foreach ($matches as $match) {
                $this->addMatch($match);
            }
        }

        return $this;
    }

    /**
     * @param  Match $match
     * @return self
     */
    public function addMatch(Match $match)
    {
        if ( ! $this->hasMatch($match) ) {
            $match->setLocation($this);
            $this->matches->add($match);
        }

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function removeMatches(Collection $matches)
    {
        if (count($matches)) {
            foreach ($matches as $match) {
                $this->removeMatch($match);
            }
        }

        return $this;
    }

    /**
     * @param  Match $match
     * @return self
     */
    public function removeMatch(Match $match)
    {
        $match->setLocation(null);
        $this->matches->removeElement($match);

        return $this;
    }

    /**
     * @param  Match $match
     * @return bool
     */
    public function hasMatch(Match $match)
    {
        return $this->matches->contains($match);
    }

    /**
     * String representation of this Location object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

}
