<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

class ExtensionEntity
{
    protected $id;

    protected $account;

    protected $firstName;

    protected $lastName;

    protected $telephoneNumber;

    protected $photoSource = 'G';

    protected $citizenship;

    public static $citizenshipMap = [
        'US' => 'U.S. Citizen',
        'RA' => 'Resident Alien',
        'NA' => 'Non-resident Alien',
        'CR' => 'Collegiate Resident',
        'CN' => 'Collegiate Non-resident',
    ];

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

    public function getPhotoSource()
    {
        return $this->photoSource ?: 'G';
    }

    public function setPhotoSource($src)
    {
        // G = Gravatar
        // C = Custom
        if (in_array($src, ['G', 'C'])) {
            $this->photoSource = $src;
        }

        return $this;
    }

    public function getCitizenship()
    {
        return $this->citizenship;
    }

    public function setCitizenship($key)
    {
        // US = US Citizen
        // RA = Resident Alien
        // NA = Non-resident Alien
        // CR = Collegiate Resident
        // CN = Collegiate Non-resident
        if (array_key_exists($key, self::$citizenshipMap)) {
            $this->citizenship = $key;
        }

        return $this;
    }

}
