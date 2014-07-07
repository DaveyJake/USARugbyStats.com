<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Team;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Form\Element\ObjectSelect;

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

        $account = new ObjectSelect();
        $account->setName('account');
        $account->setOptions(array(
            'label' => 'Account',
            'object_manager' => $om,
            'target_class'   => 'UsaRugbyStats\Account\Entity\Account',
        ));

        $this->add($account);
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
