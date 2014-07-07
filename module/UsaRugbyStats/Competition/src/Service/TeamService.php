<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use UsaRugbyStats\Competition\Entity\Team;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Account\Entity\Account;

class TeamService extends AbstractService
{
    /**
     * @var ObjectRepository
     */
    protected $teamAdministratorRepository;

    /**
     * @var ObjectRepository
     */
    protected $accountRepository;

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

    public function processTeamAdministratorsChange(Team $t, $selections)
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
