<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

class ExtensionEntity
{
    protected $id;

    protected $account;

    protected $height;

    protected $weight;

    protected $benchPress;

    protected $sprintTime;

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

    public function setAccount($account = NULL)
    {
        $this->account = $account;

        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($h)
    {
        $this->height = $h;

        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($w)
    {
        $this->weight = $w;

        return $this;
    }

    public function getBenchPress()
    {
        return $this->benchPress;
    }

    public function setBenchPress($bp)
    {
        $this->benchPress = $bp;

        return $this;
    }

    public function getSprintTime()
    {
        return $this->sprintTime;
    }

    public function setSprintTime($s)
    {
        $this->sprintTime = $s;

        return $this;
    }

}
