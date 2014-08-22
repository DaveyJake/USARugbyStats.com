<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Competition;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\Competition\MatchService;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition;
use ZfcRbac\Exception\UnauthorizedException;
use UsaRugbyStats\Competition\Service\CompetitionService;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\CompetitionApi\Extractor\CompetitionMatchExtractor;

class MatchController extends AbstractActionController
{
    use TeamServiceTrait;

    protected $competitionService;
    protected $matchService;

    public function listAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();

        $paginator = new Paginator(new CollectionAdapter($competition->getMatches()));
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('entity', $competition);
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/list');

        return $vm;
    }

    public function viewAction()
    {
        $match = $this->getMatchEntityFromRoute();

        $vm = new ViewModel();
        $vm->setVariable('match', $match);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/view');

        return $vm;
    }

    public function renderMatchRowAction()
    {
        $match = $this->getMatchEntityFromRoute();

        $vm = new ViewModel();
        $vm->setVariable('match', $match);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/partials/games-listing-row');
        $vm->setTerminal(true);

        $relativeToCompetition = $this->params()->fromQuery('relativeToCompetition', null);
        if ( !empty($relativeToCompetition) ) {
            $vm->setVariable('relativeTo', $this->getCompetitionService()->findByID($relativeToCompetition));
        }

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
        $session->form = $service->getCreateForm();
        $session->competition = $competition;
        $session->entity = new Match();
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $competition->getId();

            $result = $this->getMatchService()->create($data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was created successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_competition_match/update', [
                    'cid' => $competition->getId(),
                    'mid' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('match', $session->entity);
        $vm->setVariable('form', $session->form);
        $vm->setVariable('flags', $session->flags);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/create');

        return $vm;
    }

    public function updateAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();
        $match = $this->getMatchEntityFromRoute();
        if ( ! $this->isGranted('competition.competition.match.update', $match) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getMatchService();

        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->competition = $competition;
        $session->entity = $match;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $competition->getId();

            $result = $this->getMatchService()->update($match, $data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_competition_match/update', [
                    'cid' => $competition->getId(),
                    'mid' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('match', $session->entity);
        $vm->setVariable('form', $session->form);
        $vm->setVariable('flags', $session->flags);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/update');

        return $vm;
    }

    public function copyRosterAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();
        $entity = $this->getMatchEntityFromRoute();
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
                'url' => (string) $this->url()->fromRoute('usarugbystats_frontend_competition_match/view', [
                    'cid' => $competition->getId(),
                    'mid' => $data['match']->getId(),
                ]),
            ],
            'roster' => $data['roster'],
        ]));
    }

    public function deleteAction()
    {
        $competition = $this->getCompetitionEntityFromRoute();
        $entity = $this->getMatchEntityFromRoute();
        if ( ! $this->isGranted('competition.competition.match.delete', $entity) ) {
            throw new UnauthorizedException();
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getMatchService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The match was removed successfully!');

            return $this->redirect()->toRoute('usarugbystats_frontend_competition_match', [
                'cid' => $competition->getId(),
            ]);
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/delete');

        return $vm;
    }

    protected function getCompetitionEntityFromRoute()
    {
        $id = $this->params()->fromRoute('cid');
        $entity = $this->getCompetitionService()->findByID($id);
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

    protected function getMatchEntityFromRoute()
    {
        $id = $this->params()->fromRoute('mid');
        $entity = $this->getMatchService()->findByID($id);
        if (! $entity instanceof Match) {
            throw new \RuntimeException('No match with the specified identifier!');
        }
        if ( $entity->getCompetition()->getId() != $this->params()->fromRoute('cid') ) {
            throw new \RuntimeException('No match with the specified identifier!');
        }

        return $entity;
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
