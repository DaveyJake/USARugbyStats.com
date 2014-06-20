<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class TeamController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;

    protected $teamService;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        $team = $this->getTeamService()->findByID($id);
        if (! $team instanceof Team) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        $repository = $this->getCompetitionMatchService()->getMatchRepository();
        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $repository->findAllForTeam($team)->partition(function ($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('team', $team);
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/team/index');

        return $vm;
    }

    public function getTeamService()
    {
        if (! $this->teamService instanceof TeamService) {
            $this->setTeamService($this->getServiceLocator()->get(
                'usarugbystats_competition_team_service'
            ));
        }

        return $this->teamService;
    }

    public function setTeamService(TeamService $s)
    {
        $this->teamService = $s;

        return $this;
    }
}
