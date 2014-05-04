<?php

namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionAdminController extends AbstractActionController
{
    protected $competitionService;

    public function listAction()
    {
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
        $form = $this->getCompetitionService()->getCreateForm();
        if ( $this->getRequest()->isPost() ) {
            $result = $this->getCompetitionService()->create($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getCompetitionService()->findByID($id);
        if (! $entity instanceof Competition) {
            throw new \RuntimeException('No competition with the specified identifier!');
        }

        $form = $this->getCompetitionService()->getUpdateForm();

        // On this page we only want to edit the Competition details,
        // not any of it's associations (Divisions, Games, etc)
        $compElements = $form->get('competition')->getElements();
        $rootElements = $form->getElements();
        $form->setValidationGroup(array_merge(
            array_keys($rootElements),
            [ 'competition' => array_keys($compElements) ]
        ));

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getCompetitionService()->update($form, $this->getRequest()->getPost()->toArray(), $entity);
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit', ['id' => $result->getId()]);
            }
        } else {
            $form->bind($entity);
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/edit');

        return $vm;
    }

    public function divisionsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getCompetitionService()->findByID($id);
        if (! $entity instanceof Competition) {
            throw new \RuntimeException('No competition with the specified identifier!');
        }

        $form = $this->getCompetitionService()->getUpdateForm();

        // On this page we only want to edit the Division list
        $form->setValidationGroup(['competition' => ['divisions']]);

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getCompetitionService()->update($form, $this->getRequest()->getPost()->toArray(), $entity);
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The division assignments were updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/divisions', ['id' => $result->getId()]);
            }
        } else {
            $form->bind($entity);
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/divisions');

        return $vm;
    }

    public function matchesAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getCompetitionService()->findByID($id);
        if (! $entity instanceof Competition) {
            throw new \RuntimeException('No competition with the specified identifier!');
        }

        $form = $this->getCompetitionService()->getUpdateForm();
        $form->bind($entity);

        // On this page we only want to edit the Division list
        $form->setValidationGroup(['competition' => ['matches']]);

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getCompetitionService()->update($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition matches were updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_competitionadmin/edit/divisions', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/competition-admin/divisions');

        return $vm;
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getCompetitionService()->findByID($id);
        if (! $entity instanceof Competition) {
            throw new \RuntimeException('No competition with the specified identifier!');
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
}
