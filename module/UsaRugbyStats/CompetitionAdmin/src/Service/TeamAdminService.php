<?php
namespace UsaRugbyStats\CompetitionAdmin\Service;

use UsaRugbyStats\Competition\Service\TeamService;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use UsaRugbyStats\Competition\Entity\Team\Member as TeamMembership;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class TeamAdminService extends TeamService
{
    protected $entityClassName = 'stdClass';

    /**
     * @var ObjectRepository
     */
    protected $teamAdministratorRepository;

    /**
     * @var ObjectRepository
     */
    protected $accountRepository;

    public function __construct()
    {
        parent::__construct();

        // Ensure that events for TeamService still get triggered
        // @TODO there has to be a nicer way to do this
        array_push($this->eventIdentifier, 'UsaRugbyStats\Competition\Service\TeamService');
    }

    public function getAdministratorsForTeam(Team $t)
    {
        $rawData = $this->getTeamAdministratorRepository()->findByTeam($t->getId());
        $resultset = array();

        foreach ($rawData as $record) {
            if (! $record instanceof TeamAdmin) {
                continue;
            }

            $obj = new \stdClass();
            $obj->id = $record->getId();
            $obj->account = $record->getAccount()->getId();
            array_push($resultset, $obj);
        }

        return $resultset;
    }

    public function getMembersForTeam(Team $t)
    {
        $rawData = $this->getAccountRepository()->findAllMembersForTeam($t);
        $resultset = array();

        foreach ($rawData as $account) {
            if (! $account instanceof Account) {
                continue;
            }

            $role = $account->getRoleAssignment('member');
            if (! $role instanceof Member) {
                continue;
            }

            $membership = $role->getMembershipForTeam($t);
            if (! $membership instanceof TeamMembership) {
                continue;
            }

            $obj = new \stdClass();
            $obj->id = $membership->getId();
            $obj->account = $account->getId();
            $obj->membershipStatus = $membership->getMembershipStatus();
            array_push($resultset, $obj);
        }

        return $resultset;
    }

    /**
     * Create new entity from form data
     *
     * @param  array         $data
     * @return stdClass|NULL
     */
    public function create(array $data)
    {
        $entity = parent::create($data);

        if (! $entity instanceof $this->entityClassName) {
            return $entity;
        }

        if ( !isset($data['administrators']) ) {
            $data['administrators'] = array();
        }
        $this->processTeamAdministratorsChange($entity->team, $data['administrators']);

        if ( !isset($data['members']) ) {
            $data['members'] = array();
        }
        $this->processTeamMembersChange($entity->team, $data['members']);

        return $entity;
    }

    /**
     * Update existing entity with new data
     *
     * @param  stdClass                 $entity
     * @param  array                    $data
     * @return stdClass|NULL
     * @throws InvalidArgumentException when $entity isn't of same type as repository entity
     */
    public function update($entity, array $data)
    {
        // If we're deleting all the administrator records, empty before form bind
        // to suppress input validator failures on missing records (@see GH-15)
        if ( !isset($data['administrators']) || empty($data['administrators']) ) {
            $entity->administrators = array();
            $fsAdministrators = $this->session->form->remove('administrators');
        }

        // If we're deleting all the member records, empty before form bind
        // to suppress input validator failures on missing records (@see GH-15)
        if ( !isset($data['members']) || empty($data['members']) ) {
            $entity->members = array();
            $fsMembers = $this->session->form->remove('members');
        }

        // Run the embedded Team entity through the normal, Doctrine-linked process
        $result = parent::update($entity, $data);
        if (! $result instanceof $this->entityClassName) {
            return $result;
        }

        if ( !isset($data['administrators']) ) {
            $data['administrators'] = array();
        }
        $this->processTeamAdministratorsChange($result->team, $data['administrators']);

        if ( !isset($data['members']) ) {
            $data['members'] = array();
        }
        $this->processTeamMembersChange($entity->team, $data['members']);

        return $result;
    }

    /**
     * Override AbstractService::save to send only Doctrine-connected entity to save
     *
     * @param stdClass $entity
     */
    public function save($entity)
    {
        if ( ! $entity instanceof Team && ! isset($entity->team) ) {
            return false;
        }

        return parent::save($entity instanceof Team ? $entity : $entity->team);
    }

    /**
     * Override AbstractService::save to send only Doctrine-connected entity to remove
     *
     * @param stdClass $entity
     */
    public function remove($entity)
    {
        if ( ! $entity instanceof Team && ! isset($entity->team) ) {
            return false;
        }

        return parent::remove($entity instanceof Team ? $entity : $entity->team);
    }

    /**
     * Process the supplied Team Administrator account selections and
     * create/update TeamAdmin RBAC role objects where appropriate
     *
     * @param  Team    $t
     * @param  array   $selections
     * @return boolean
     */
    public function processTeamAdministratorsChange(Team $t, array $selections)
    {
        $currentSet = $this->getAdministratorsForTeam($t);

        // Process add/change
        $processed = [];
        foreach ($selections as $selection) {
            if ( !isset($selection['id']) ) {
                continue;
            }
            if ( empty($selection['id']) ) {
                // This is a new association...

                // Fetch the account
                $account = $this->getAccountRepository()->find($selection['account']);
                if (! $account instanceof Account) {
                    continue;
                }

                // Load any existing TeamAdmin record (or create a new one)
                // and associate the team with it
                $roleAssignment = $account->getRoleAssignment('team_admin');
                if (! $roleAssignment instanceof TeamAdmin) {
                    $roleAssignment = new TeamAdmin();
                    $roleAssignment->setAccount($account);
                }
                $roleAssignment->addManagedTeam($t);

                $this->getObjectManager()->persist($roleAssignment);
                continue;
            }

            $record = $this->getTeamAdministratorRepository()->find($selection['id']);
            if (! $record instanceof TeamAdmin) {
                continue;
            }

            // Existing selection, but ensure the team is indeed selected
            if ( ! $record->hasManagedTeam($t) ) {
                $record->addManagedTeam($t);
                $this->getObjectManager()->persist($record);
            }

            array_push($processed, $selection['id']);
        }

        foreach ($currentSet as $currentItem) {
            $item = $this->getTeamAdministratorRepository()->find($currentItem->id);
            if (! $item instanceof TeamAdmin) {
                continue;
            }

            // If the existing TeamAdmin record has this team but the POST data
            // from the form does not, drop the team from the TeamAdmin record
            if ( $item->hasManagedTeam($t) && !in_array($item->getId(), $processed) ) {
                $item->removeManagedTeam($t);
                $this->getObjectManager()->persist($item);
            }
        }

        $this->getObjectManager()->flush();

        return true;
    }

    /**
     * Process the supplied Team Members account selections and
     * create/update Member RBAC role objects where appropriate
     *
     * @param  Team    $t
     * @param  array   $selections
     * @return boolean
     */
    public function processTeamMembersChange(Team $t, array $selections)
    {
        $currentSet = $this->getMembersForTeam($t);

        // Process add/change
        $processed = [];
        foreach ($selections as $selection) {
            if ( !isset($selection['id']) ) {
                continue;
            }
            if ( empty($selection['id']) ) {
                // This is a new association...

                // Fetch the account
                $account = $this->getAccountRepository()->find($selection['account']);
                if (! $account instanceof Account) {
                    continue;
                }

                // Load any existing Member record (or create a new one)
                $roleAssignment = $account->getRoleAssignment('member');
                if (! $roleAssignment instanceof Member) {
                    $roleAssignment = new Member();
                    $roleAssignment->setAccount($account);
                    $roleAssignment->setRole(new Role('member'));
                }

                // Load any existing membership record for this player and team
                $teamMembership = $roleAssignment->getMembershipForTeam($t);
                if (! $teamMembership instanceof TeamMembership) {
                    $teamMembership = new TeamMembership();
                    $teamMembership->setTeam($t);
                    $roleAssignment->addMembership($teamMembership);
                }
                $teamMembership->setMembershipStatus(@$selection['membershipStatus']);

                $this->getObjectManager()->persist($roleAssignment);
                continue;
            }

            $record = $t->getMemberById($selection['id']);
            if (! $record instanceof TeamMembership) {
                continue;
            }

            if ( $record->getMembershipStatus() != @$selection['membershipStatus'] ) {
                $record->setMembershipStatus(@$selection['membershipStatus']);
                $this->getObjectManager()->persist($record);
            }

            array_push($processed, $selection['id']);
        }

        foreach ($currentSet as $currentItem) {
            if ( in_array($currentItem->id, $processed) ) {
                continue;
            }

            $record = $t->getMemberById($currentItem->id);
            if (! $record instanceof TeamMembership) {
                continue;
            }

            $t->removeMember($record);
        }

        $this->getObjectManager()->persist($t);
        $this->getObjectManager()->flush();

        return true;
    }

    /**
     * @return ObjectRepository
     */
    public function getTeamAdministratorRepository()
    {
        return $this->teamAdministratorRepository;
    }

    /**
     * @param ObjectRepository $obj
     */
    public function setTeamAdministratorRepository(ObjectRepository $obj)
    {
        $this->teamAdministratorRepository = $obj;

        return $this;
    }

    /**
     * @return ObjectRepository
     */
    public function getAccountRepository()
    {
        return $this->accountRepository;
    }

    /**
     * @param ObjectRepository $obj
     */
    public function setAccountRepository(ObjectRepository $obj)
    {
        $this->accountRepository = $obj;

        return $this;
    }
}
