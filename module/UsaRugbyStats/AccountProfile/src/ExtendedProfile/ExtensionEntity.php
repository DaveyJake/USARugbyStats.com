<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

class ExtensionEntity
{
    protected $id;

    protected $account;

    protected $firstName;

    protected $lastName;

    protected $telephoneNumber;

    protected $membershipStatus;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account = null)
    {
        $this->account = $account;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($first)
    {
        $this->firstName = $first;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($last)
    {
        $this->lastName = $last;

        return $this;
    }

    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber($tel)
    {
        $this->telephoneNumber = $tel;

        return $this;
    }

    public function getMembershipStatus()
    {
        return $this->membershipStatus;
    }

    public function setMembershipStatus($status)
    {
        if ( ! in_array($status, self::getMembershipStatusValues(), true) ) {
            throw new \InvalidArgumentException('Invalid membership status!');
        }
        $this->membershipStatus = ($status == true);

        return $this;
    }

    public static function getMembershipStatusValues()
    {
        return array(
            'C' => 'Current',
            'NC' => 'Not Current',
            'P' => 'Pending',
            'D' => 'Deceased',
        );
    }
}
