<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Dashboard;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class UnionAdminController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;

    public function indexAction()
    {
        $repository = $this->getCompetitionMatchService()->getMatchRepository();
        $user = $this->zfcUserAuthentication()->getIdentity();
        $role = $user->getRoleAssignment('union_admin');

        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $repository->findAllForUnion($role->getManagedUnions())->partition(function ($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/dashboard/union-admin/index');

        return $vm;
    }

}
