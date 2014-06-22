<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use DoctrineModule\Form\Element\ObjectSelect;
use Doctrine\Common\Persistence\ObjectRepository;

class UnionAdminFieldset extends RoleAssignmentFieldset
{
    protected $unionRepo;

    public function __construct(ObjectManager $om, ObjectRepository $unionRepo)
    {
        parent::__construct('union_admin');

        $this->unionRepo = $unionRepo;

        $union = new ObjectSelect();
        $union->setName('union');
        $union->setOptions(array(
            'label' => 'Union',
            'object_manager' => $om,
            'target_class'   => 'UsaRugbyStats\Competition\Entity\Union',
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'managedUnions',
            'options' => array(
                'label' => 'Managed Unions',
                'target_element' => $union,
                'should_create_template' => true,
            )
        ));
    }

    public function getDisplayName() { return 'Union Administrator'; }

    public function getUnion($unionid)
    {
        if (empty($unionid)) {
            return null;
        }

        return $this->unionRepo->find($unionid);
    }
}
