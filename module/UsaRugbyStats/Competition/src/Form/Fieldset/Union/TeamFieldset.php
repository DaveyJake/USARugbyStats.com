<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Union;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectRepository;

class TeamFieldset extends Fieldset
{
    protected $teamRepo;

    public function __construct(ObjectManager $om, ObjectRepository $mapper)
    {
        parent::__construct('team');

        $this->teamRepo = $mapper;

        $team = new ObjectSelect();
        $team->setName('id');
        $team->setOptions(array(
            'label' => 'Team',
            'object_manager' => $om,
            'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            'find_method'    => array(
                'name'   => 'findBy',
                'params' => array(
                    'criteria' => array(),
                    'orderBy'  => array('name' => 'ASC'),
                ),
            ),
        ));

        $this->add($team);
    }

    public function getTeam($teamid = null)
    {
        if (empty($teamid)) {
            $teamid = $this->get('id')->getValue();
        }
        if (empty($teamid)) {
            return null;
        }

        return $this->teamRepo->find($teamid);
    }
}
