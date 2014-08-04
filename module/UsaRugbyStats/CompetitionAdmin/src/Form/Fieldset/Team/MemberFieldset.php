<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Form\Element\ObjectSelect;

class MemberFieldset extends Fieldset
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
            'find_method'    => array(
                'name'   => 'findBy',
                'params' => array(
                    'criteria' => array(),
                    'orderBy'  => array('displayName' => 'ASC'),
                ),
            ),
        ));
        $this->add($account);

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'membershipStatus',
                'options' => array(
                    'label' => 'Status',
                    'value_options' => array(
                        NULL => 'Not Specified',
                        0 => 'Unpaid',
                        1 => 'Pending',
                        2 => 'Current',
                        3 => 'Grace Period',
                        4 => 'Lapsed',
                    ),
                ),
            )
        );
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
