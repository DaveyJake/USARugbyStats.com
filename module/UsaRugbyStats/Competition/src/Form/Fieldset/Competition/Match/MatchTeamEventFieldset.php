<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ProvidesObjectManager;

class MatchTeamEventFieldset extends Fieldset
{
    use ProvidesObjectManager;

    protected $team;

    public function __construct($name, ObjectManager $om)
    {
        parent::__construct($name);
        $this->setObjectManager($om);

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'minute',
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'event',
        ));
    }

    public function setTeam($obj)
    {
        $this->team = $obj;

        return $this;
    }

    public function getTeam()
    {
        return $this->team;
    }

}
