<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use UsaRugbyStats\Competition\Entity\Team\Member as TeamMembership;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Repository\Rbac\RoleAssignment\TeamAdminRepository;

class TeamService extends AbstractService
{
    /**
     * @var TeamAdminRepository
     */
    protected $teamAdminRoleAssignmentRepository;

    public function fetchAll(Criteria $c = null)
    {
        if ( is_null($c) ) {
            $c = new Criteria();
        }
        $orderings = $c->getOrderings();
        if ( empty($orderings) ) {
            $c->orderBy(['name' => 'ASC']);
        }

        return parent::fetchAll($c);
    }

    public function findAllBySearchQuery($q, Criteria $c = null)
    {
        if ( is_null($c) ) {
            $c = new Criteria();
        }
        $orderings = $c->getOrderings();
        if ( empty($orderings) ) {
            $c->orderBy(['name' => 'ASC']);
        }

        $c->where($c->expr()->orX(
            $c->expr()->contains('name', $q),
            $c->expr()->contains('remoteId', $q),
            $c->expr()->contains('email', $q)
        ));

        $adapter = new Selectable($this->getRepository(), $c);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    public function findByRemoteID($id)
    {
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->findOneBy(['remoteId' => $id]);
    }

    public function getAdministratorsForTeam($t)
    {
        $teamid = $t instanceof Team ? $t->getId() : (int) $t;
        $rawData = $this->getTeamAdminRoleAssignmentRepository()->findByTeam($teamid);
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

    public function getMembersForTeam($t)
    {
        $team = $t instanceof Team
              ? $t
              : $this->findByID((int) $t);
        if (! $team instanceof Team) {
            return array();
        }

        $rawData = $team->getMembers();
        $resultset = array();

        foreach ($rawData as $membership) {

            if (! $membership instanceof TeamMembership) {
                continue;
            }

            $role = $membership->getRole();
            if (! $role instanceof Member) {
                continue;
            }

            $account = $role->getAccount();
            if (! $account instanceof Account) {
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
     * @return TeamAdminRepository
     */
    public function getTeamAdminRoleAssignmentRepository()
    {
        return $this->teamAdminRoleAssignmentRepository;
    }

    public function setTeamAdminRoleAssignmentRepository(TeamAdminRepository $repo)
    {
        $this->teamAdminRoleAssignmentRepository = $repo;

        return $this;
    }

}
