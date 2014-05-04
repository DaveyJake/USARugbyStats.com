<?php
namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\Competition\Entity\Team;

class TeamAdminController extends AbstractActionController
{
    protected $teamService;

    public function listAction()
    {
        $svc = $this->getTeamService();

        $paginator = $svc->fetchAll();
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/list');

        return $vm;
    }

    public function createAction()
    {
        $form = $this->getTeamService()->getCreateForm();
        if ( $this->getRequest()->isPost() ) {
            $result = $this->getTeamService()->create($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Team) {
                $this->flashMessenger()->addSuccessMessage('The team was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getTeamService()->findByID($id);
        if (! $entity instanceof Team) {
            throw new \RuntimeException('No team with the specified identifier!');
        }

        $form = $this->getTeamService()->getUpdateForm();
        $form->bind($entity);

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getTeamService()->update($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Team) {
                $this->flashMessenger()->addSuccessMessage('The team was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/edit');

        return $vm;
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getTeamService()->findByID($id);
        if (! $entity instanceof Team) {
            throw new \RuntimeException('No team with the specified identifier!');
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getTeamService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The team was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/list');
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/remove');

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
