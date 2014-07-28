<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use UsaRugbyStats\Competition\Form\Fieldset\Team\MemberFieldset as TeamMemberFieldset;

class MemberFieldset extends RoleAssignmentFieldset
{
    public function __construct(TeamMemberFieldset $tmfs)
    {
        parent::__construct('member');

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'memberships',
            'options' => array(
                'label' => 'Memberships',
                'target_element' => $tmfs,
                'should_create_template' => true,
                'template_placeholder' => '__memberindex__',
            )
        ));
    }

    public function getDisplayName() { return 'Member'; }
}
