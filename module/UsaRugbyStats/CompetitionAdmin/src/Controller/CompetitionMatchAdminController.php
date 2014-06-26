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

class CompetitionMatchAdminController extends AbstractActionController
{
    protected $competitionService;
    protected $matchService;

    public function listAction()
    {
        $entity = $this->loadCompetitionEntity();

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
        $entity = $this->loadCompetitionEntity();

        $form = $this->getMatchService()->getCreateForm();
        $form->get('match')->get('competition')->setValue($entity->getId());

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $entity->getId();

            $result = $this->getMatchService()->create($data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/matches/edit', [
                    'id' => $entity->getId(),
                    'match' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/create');

        return $vm;
    }

    public function editAction()
    {
        $competition = $this->loadCompetitionEntity();

        $id = $this->params()->fromRoute('match');
        $entity = $this->getMatchService()->findByID($id);
        if (! $entity instanceof Match) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }
        if ( $entity->getCompetition() != null && $entity->getCompetition()->getId() != $competition->getId()) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }

        $form = $this->getMatchService()->getUpdateForm();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();
            $data['match']['competition'] = $competition->getId();

            $result = $this->getMatchService()->update($entity, $data);
            if ($result instanceof Match) {
                $this->flashMessenger()->addSuccessMessage('The match was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/matches/edit', [
                    'id' => $competition->getId(),
                    'match' => $result->getId(),
                ]);
            }
        } else {
            $form->bind($entity);
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/matches/edit');

        return $vm;
    }

    public function removeAction()
    {
        $competition = $this->loadCompetitionEntity();

        $id = $this->params()->fromRoute('match');
        $entity = $this->getMatchService()->findByID($id);
        if (! $entity instanceof Match) {
            throw new \RuntimeException('No match found with the specified identifier!');
        }
        if ( $entity->getCompetition() != null && $entity->getCompetition()->getId() != $competition->getId()) {
            throw new \RuntimeException('No match found with the specified identifier!');
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

    protected function loadCompetitionEntity()
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
