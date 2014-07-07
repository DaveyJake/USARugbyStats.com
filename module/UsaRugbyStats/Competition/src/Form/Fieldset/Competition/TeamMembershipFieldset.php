<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class TeamMembershipFieldset extends Fieldset
{
    protected $teamRepo;

    public function __construct(ObjectManager $om)
    {
        parent::__construct('team-membership');

        $this->teamRepo = $om->getRepository('UsaRugbyStats\Competition\Entity\Team');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'team',
            'options' => array(
                'label' => 'Team',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
            ),
        ));

    }

    public function getTeam($teamid = null)
    {
        if (empty($teamid)) {
            $teamid = $this->get('team')->getValue();
        }
        if (empty($teamid)) {
            return null;
        }

        return $this->teamRepo->find($teamid);
    }
}
