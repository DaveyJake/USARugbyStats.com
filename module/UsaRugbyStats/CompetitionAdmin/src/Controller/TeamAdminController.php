<?php
namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\CompetitionAdmin\Service\TeamAdminService;
use UsaRugbyStats\Competition\Entity\Team;
use ZfcRbac\Exception\UnauthorizedException;

class TeamAdminController extends AbstractActionController
{
    /**
     * @var TeamAdminService
     */
    protected $teamAdminService;

    public function listAction()
    {
        if ( ! $this->isGranted('competition.team.list') ) {
            throw new UnauthorizedException();
        }

        $svc = $this->getTeamAdminService();

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
        if ( ! $this->isGranted('competition.team.create') ) {
            throw new UnauthorizedException();
        }

        $service = $this->getTeamAdminService();

        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->entity = new \stdClass();
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $formData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $this->getTeamAdminService()->create($formData);
            if ( isset($result->team) && $result->team instanceof Team) {
                $this->flashMessenger()->addSuccessMessage('The team was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/edit', ['id' => $result->team->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        $team = $this->getTeamAdminService()->findByID($id);
        if (! $team instanceof Team) {
            throw new \RuntimeException('No team with the specified identifier!');
        }

        if ( ! $this->isGranted('competition.team.update', $team) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getTeamAdminService();

        $entity = new \stdClass();
        $entity->team = $team;
        $entity->administrators = $service->getAdministratorsForTeam($team);
        $entity->members = $service->getMembersForTeam($team);

        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->entity = $entity;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $formData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $this->getTeamAdminService()->update($entity, $formData);
            if ( isset($result->team) && $result->team instanceof Team) {
                $this->flashMessenger()->addSuccessMessage('The team was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/edit', ['id' => $result->team->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $team);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/edit');

        return $vm;
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getTeamAdminService()->findByID($id);
        if (! $entity instanceof Team) {
            throw new \RuntimeException('No team with the specified identifier!');
        }

        if ( ! $this->isGranted('competition.team.delete', $entity) ) {
            throw new UnauthorizedException();
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getTeamAdminService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The team was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_teamadmin/list');
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/remove');

        return $vm;
    }

    public function getTeamAdminService()
    {
        if (! $this->teamAdminService instanceof TeamAdminService) {
            $this->setTeamAdminService($this->getServiceLocator()->get(
                'usarugbystats_competition-admin_team_service'
            ));
        }

        return $this->teamAdminService;
    }

    public function setTeamAdminService(TeamAdminService $s)
    {
        $this->teamAdminService = $s;

        return $this;
    }
}
