<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionStandingsServiceTrait;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\Competition\Traits\PlayerStatisticsServiceTrait;
use ZfcRbac\Exception\UnauthorizedException;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team\Member;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member as MemberRoleAssignment;
use UsaRugbyStats\Application\Entity\AccountInterface;

class TeamController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;
    use CompetitionServiceTrait;
    use CompetitionStandingsServiceTrait;
    use TeamServiceTrait;
    use PlayerStatisticsServiceTrait;

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

        $league = $this->getCompetitionService()->findLeagueCompetitionForTeam($team);
        $leagueStandings = $league instanceof Competition
            ? $this->getCompetitionStandingsService()->getStandingsFor($league)
            : null;

        $administrators = $this->getTeamService()->getAdministratorsForTeam($team);

        $lastMatchRoster = $this->getCompetitionMatchService()->getLastMatchRosterForTeam($team, new \DateTime());
        $players = [];
        foreach ( $team->getMembers() as $membership ) {
            if ( ! ( $membershipRole = $membership->getRole() ) instanceof MemberRoleAssignment ) {
                continue;
            }
            if ( ! ( $membershipAccount = $membershipRole->getAccount() ) instanceof AccountInterface ) {
                continue;
            }
            $position = array_search($membershipAccount->getId(), @$lastMatchRoster['roster'] ?: array(), true);
            array_push($players, [
                'player' => $membershipAccount,
                'position' => $position,
                'stats' => $this->getPlayerStatisticsService()->getStatisticsFor($membershipAccount),
            ]);
        }

        $vm = new ViewModel();
        $vm->setVariable('team', $team);
        $vm->setVariable('matches', $this->getCompetitionMatchService()->findAllForTeam($team));
        $vm->setVariable('league', $league);
        $vm->setVariable('leagueStandings', $leagueStandings);
        $vm->setVariable('administrators', $administrators);
        $vm->setVariable('players', $players);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/team/index');

        return $vm;
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        $service = $this->getTeamService();

        $team = $service->findByID($id);
        if (! $team instanceof Team) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        if ( ! $this->isGranted('competition.team.update', $team) ) {
            throw new UnauthorizedException();
        }

        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->entity = $team;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $formData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $this->getTeamService()->update($team, $formData);
            if ($result instanceof Team) {
                $this->flashMessenger()->addSuccessMessage('The team was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_team/update', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $team);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/team/update');

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
