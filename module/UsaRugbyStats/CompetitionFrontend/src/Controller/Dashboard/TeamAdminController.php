<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Dashboard;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use ZfcRbac\Exception\UnauthorizedException;

class TeamAdminController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;

    public function indexAction()
    {
        $repository = $this->getCompetitionMatchService()->getRepository();
        $user = $this->zfcUserAuthentication()->getIdentity();
        $role = $user->getRoleAssignment('team_admin');
        if (! $role instanceof TeamAdmin) {
            throw new UnauthorizedException('You are not a team administrator!');
        }

        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $repository->findAllForTeam($role->getManagedTeams())->partition(function ($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/dashboard/team-admin/index');

        return $vm;
    }

}
