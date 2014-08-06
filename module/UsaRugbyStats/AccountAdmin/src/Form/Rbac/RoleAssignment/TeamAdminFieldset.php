<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use UsaRugbyStats\Application\Common\ObjectSelect;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Application\Common\ElementCollectionHydrator;

class TeamAdminFieldset extends RoleAssignmentFieldset
{
    protected $teamRepo;

    public function __construct(ObjectManager $om, ObjectRepository $teamRepo)
    {
        parent::__construct('team_admin');

        $this->teamRepo = $teamRepo;

        $team = new ObjectSelect();
        $team->setName('team');
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

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'managedTeams',
            'options' => array(
                'label' => 'Managed Teams',
                'target_element' => $team,
                'should_create_template' => true,
                'template_placeholder' => '__teamindex__',
            )
        ));
        $this->get('managedTeams')->setHydrator(new ElementCollectionHydrator($teamRepo));
    }

    public function getDisplayName() { return 'Team Administrator'; }

    public function getTeam($teamid)
    {
        if (empty($teamid)) {
            return null;
        }

        return $this->teamRepo->find($teamid);
    }
}
