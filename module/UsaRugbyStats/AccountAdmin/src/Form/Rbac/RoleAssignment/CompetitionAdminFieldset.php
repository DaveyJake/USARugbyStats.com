<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Application\Common\ObjectSelect;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Application\Common\ElementCollectionHydrator;

class CompetitionAdminFieldset extends RoleAssignmentFieldset
{
    protected $competitionRepo;

    public function __construct(ObjectManager $om, ObjectRepository $competitionRepo)
    {
        parent::__construct('competition_admin');

        $this->competitionRepo = $competitionRepo;

        $competition = new ObjectSelect();
        $competition->setName('competition');
        $competition->setOptions(array(
            'label' => 'Competition',
            'object_manager' => $om,
            'target_class'   => 'UsaRugbyStats\Competition\Entity\Competition',
            'find_method'    => array(
                'name'   => 'findBy',
                'params' => array(
                    'criteria' => array(),
                    'orderBy'  => array( 'name' => 'ASC' ),
                ),
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'managedCompetitions',
            'options' => array(
                'label' => 'Managed Competitions',
                'target_element' => $competition,
                'should_create_template' => true,
                'template_placeholder' => '__compindex__',
            )
        ));
        $this->get('managedCompetitions')->setHydrator(new ElementCollectionHydrator($competitionRepo));
    }

    public function getDisplayName() { return 'Competition Administrator'; }

    public function getCompetition($id)
    {
        if (empty($id)) {
            return null;
        }

        return $this->competitionRepo->find($id);
    }
}
