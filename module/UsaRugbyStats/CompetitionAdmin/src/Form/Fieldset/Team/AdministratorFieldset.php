<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\Form\Fieldset;

class AdministratorFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('team-administrator');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'account'
        ));

    }
}
