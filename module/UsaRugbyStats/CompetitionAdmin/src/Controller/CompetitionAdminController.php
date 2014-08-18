<?php

namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Service\Competition\StandingsService;
use ZfcRbac\Exception\UnauthorizedException;

class CompetitionAdminController extends AbstractActionController
{
    protected $competitionService;
    protected $competitionStandingsService;

    public function listAction()
    {
        if ( ! $this->isGranted('competition.competition.list') ) {
            throw new UnauthorizedException();
        }

        $svc = $this->getCompetitionService();

        $paginator = $svc->fetchAll();
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/list');

        return $vm;
    }

    public function createAction()
    {
        if ( ! $this->isGranted('competition.competition.create') ) {
            throw new UnauthorizedException();
        }

        $service = $this->getCompetitionService();

        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->entity = new Competition();
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $result = $service->create($this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $entity = $this->getCompetitionEntityFromRoute();

        if ( ! $this->isGranted('competition.competition.update', $entity) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getCompetitionService();

        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->entity = $entity;

        // On this page we only want to edit the Competition details,
        // not any of it's associations (Divisions, Games, etc)
        $compElements = $session->form->get('competition')->getElements();
        $rootElements = $session->form->getElements();
        $session->form->setValidationGroup(array_merge(
            array_keys($rootElements),
            [ 'competition' => array_keys($compElements) ]
        ));

        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $result = $service->update($entity, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/edit');

        return $vm;
    }

    public function divisionsAction()
    {
        $entity = $this->getCompetitionEntityFromRoute();

        $service = $this->getCompetitionService();

        // On this page we only want to edit the Division list
        $form = $service->getUpdateForm();
        $vg = $form->getValidationGroup();
        $form->setValidationGroup([
            'competition' => [
                'divisions' => $vg['competition']['divisions']
            ]
        ]);

        $session = $service->startSession();
        $session->form = $form;
        $session->entity = $entity;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {

            // If they don't have the Division update permission short-circuit
            if ( ! $this->isGranted('competition.competition.update.divisions', $entity) ) {
                throw new UnauthorizedException();
                continue;
            }

            // If they've removed everything make sure we send an empty array
            $data = $this->getRequest()->getPost()->toArray();
            if ( ! isset($data['competition']['divisions']) || empty($data['competition']['divisions']) ) {
                $data['competition']['divisions'] = array();
            }

            $result = $service->update($entity, $data);
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The division assignments were updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/divisions', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/divisions');

        return $vm;
    }

    public function viewStandingsAction()
    {
        $entity = $this->getCompetitionEntityFromRoute();

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('page', 'standings');
        $vm->setVariable('standings', $this->getCompetitionStandingsService()->getStandingsFor($entity));
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/view/standings');

        return $vm;
    }

    public function removeAction()
    {
        $entity = $this->getCompetitionEntityFromRoute();

        if ( ! $this->isGranted('competition.competition.delete', $entity) ) {
            throw new UnauthorizedException();
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getCompetitionService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The competition was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/list');
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/remove');

        return $vm;
    }

    public function getCompetitionEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $competition = $this->getCompetitionService()->findByID($id);
        if (! $competition instanceof Competition) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        return $competition;
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

    public function getCompetitionStandingsService()
    {
        if (! $this->competitionStandingsService instanceof StandingsService) {
            $this->setCompetitionStandingsService($this->getServiceLocator()->get(
                'usarugbystats_competition_competition_standings_service'
            ));
        }

        return $this->competitionStandingsService;
    }

    public function setCompetitionStandingsService(StandingsService $s)
    {
        $this->competitionStandingsService = $s;

        return $this;
    }
}
