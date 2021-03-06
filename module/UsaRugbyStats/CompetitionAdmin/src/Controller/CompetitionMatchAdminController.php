<?php

namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use UsaRugbyStats\Competition\Service\Competition\MatchService;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use ZfcRbac\Exception\UnauthorizedException;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\Competition\Entity\Team;

class CompetitionMatchAdminController extends AbstractActionController
{
    use TeamServiceTrait;

    protected $competitionService;
    protected $matchService;

    public function listAction()
    {
        $entity = $this->getCompetitionEntityFromRoute();
        if ( ! $this->isGranted('competition.competition.match.list', $entity) ) {
            throw new UnauthorizedException();
        }

        $paginator = new Paginator(new CollectionAdapter($entity->getMatches()));
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/list');

        return $vm;
    }

    public function createAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();
        if ( ! $this->isGranted('competition.competition.match.create', $competition) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getMatchService();

        $session = $service->startSession();
        $session->competition = $competition;
        $session->form = $service->getCreateForm();
        $session->entity = new Match();
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $competition->getId();

            $result = $this->getMatchService()->create($data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/matches/edit', [
                    'id' => $competition->getId(),
                    'match' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('form', $session->form);
        $vm->setVariable('flags', $session->flags);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/create');

        return $vm;
    }

    public function editAction()
    {
        $service = $this->getMatchService();
        $competition = $this->getCompetitionEntityFromRoute();

        $id = $this->params()->fromRoute('match');
        $match = $service->findByID($id);
        if (! $match instanceof Match) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }
        if ( $match->getCompetition() != null && $match->getCompetition()->getId() != $competition->getId()) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }

        if ( ! $this->isGranted('competition.competition.match.update', $match) ) {
            throw new UnauthorizedException();
        }

        $session = $service->startSession();
        $session->competition = $competition;
        $session->form = $service->getUpdateForm();
        $session->entity = $match;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $competition->getId();

            $result = $service->update($match, $data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/matches/edit', [
                    'id' => $competition->getId(),
                    'match' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('match', $session->entity);
        $vm->setVariable('form', $session->form);
        $vm->setVariable('flags', $session->flags);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/edit');

        return $vm;
    }

    public function copyRosterAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();
        $id = $this->params()->fromRoute('match');
        $entity = $this->getMatchService()->findByID($id);
        if (! $entity instanceof Match) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }
        if ( $entity->getCompetition() != null && $entity->getCompetition()->getId() != $competition->getId()) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }

        if ( ! $this->isGranted('competition.competition.match.update', $entity) ) {
            throw new UnauthorizedException();
        }

        $teamid = $this->params()->fromRoute('team');
        $team = $this->getTeamService()->findByID($teamid);
        if (! $team instanceof Team) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }

        $data = $this->getMatchService()->getLastMatchRosterForTeam($team, $entity);
        if ( is_null($data) ) {
            return $this->getResponse()->setStatusCode(404)->setContent("No Previous Match Found");
        }

        return $this->getResponse()->setContent(json_encode([
            'match' => [
                'id' => $data['match']->getId(),
                'title' => $data['match']->getDate()->format('Y-m-d'),
                'url' => (string) $this->url()->fromRoute('usarugbystats_frontend_competition_match', [
                    'cid' => $competition->getId(),
                    'mid' => $data['match']->getId(),
                ]),
            ],
            'roster' => $data['roster'],
        ]));
    }

    public function removeAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();

        $id = $this->params()->fromRoute('match');
        $entity = $this->getMatchService()->findByID($id);
        if (! $entity instanceof Match) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }
        if ( $entity->getCompetition() != null && $entity->getCompetition()->getId() != $competition->getId()) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }

        if ( ! $this->isGranted('competition.competition.match.delete', $entity) ) {
            throw new UnauthorizedException();
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getMatchService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The match was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/matches/list', [
                'id' => $competition->getId(),
                'match' => $entity->getId(),
            ]);
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/remove');

        return $vm;
    }

    protected function getCompetitionEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        $svc = $this->getCompetitionService();
        $entity = $svc->findByID($id);
        if (! $entity instanceof Competition) {
            throw new \RuntimeException('No competition with the specified identifier!');
        }

        return $entity;
    }

    public function getCompetitionService()
    {
        if (! $this->competitionService instanceof CompetitionService) {
            $this->setCompetitionService($this->getServiceLocator()->get(
                'usarugbystats_competition_competition_service'
            ));
        }

        return $this->competitionService;
    }

    public function setCompetitionService(CompetitionService $s)
    {
        $this->competitionService = $s;

        return $this;
    }

    public function getMatchService()
    {
        if (! $this->matchService instanceof MatchService) {
            $this->setMatchService($this->getServiceLocator()->get(
                'usarugbystats_competition_competition_match_service'
            ));
        }

        return $this->matchService;
    }

    public function setMatchService(MatchService $s)
    {
        $this->matchService = $s;

        return $this;
    }
}
