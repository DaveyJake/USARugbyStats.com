<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Account\Entity\Account;

/**
 * Match Signature Entity
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchSignature
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
     * @var Account
     */
    protected $account;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var integer
     */
    protected $timestamp;

    public function __construct()
    {
        $this->timestamp = new \DateTime();
    }

    /**
     * Signature Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Signature Identifier
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
     * Account responsible for this signature
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set the account responsible for this signature
     *
     * @param  Account $u
     * @return self
     */
    public function setAccount(Account $u = NULL)
    {
        $this->account = $u;

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
        if ( ! in_array($type, ['HC','AC','REF','NR4']) ) {
            throw new \InvalidArgumentException('Signature type must be HC, AC, REF or NR4');
        }
        $this->type = $type;

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $ts)
    {
        $this->timestamp = $ts;

        return $this;
    }

}
