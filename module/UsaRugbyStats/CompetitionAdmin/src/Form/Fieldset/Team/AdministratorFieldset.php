<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Application\Common\ObjectSelect;
use Zend\Form\Element\Select;

class AdministratorFieldset extends Fieldset
{
    protected $accountRepo;

    public function __construct(ObjectManager $om, ObjectRepository $mapper)
    {
        parent::__construct('team-administrator');

        $this->accountRepo = $mapper;

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'account'
        ));

    }

    public function getAccount($id = null)
    {
        if (empty($id)) {
            $id = $this->get('account')->getValue();
        }
        if (empty($id)) {
            return null;
        }

        return $this->accountRepo->find($id);
    }
}
