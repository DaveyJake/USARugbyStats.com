<?php

namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\UnionService;
use UsaRugbyStats\Competition\Entity\Union;

class UnionAdminController extends AbstractActionController
{
    protected $unionService;

    public function listAction()
    {
        $svc = $this->getUnionService();

        $paginator = $svc->fetchAll();
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-admin/union-admin/list');

        return $vm;
    }

    public function createAction()
    {
        $form = $this->getUnionService()->getCreateForm();
        if ( $this->getRequest()->isPost() ) {
            $result = $this->getUnionService()->create($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Union) {
                $this->flashMessenger()->addSuccessMessage('The union was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_unionadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/union-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getUnionService()->findByID($id);
        if (! $entity instanceof Union) {
            throw new \RuntimeException('No union with the specified identifier!');
        }

        $form = $this->getUnionService()->getUpdateForm();
        $form->bind($entity);

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getUnionService()->update($form, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Union) {
                $this->flashMessenger()->addSuccessMessage('The union was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_unionadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/union-admin/edit');

        return $vm;
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getUnionService()->findByID($id);
        if (! $entity instanceof Union) {
            throw new \RuntimeException('No union with the specified identifier!');
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getUnionService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The union was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_unionadmin/list');
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/union-admin/remove');

        return $vm;
    }

    public function getUnionService()
    {
        if (! $this->unionService instanceof UnionService) {
            $this->setUnionService($this->getServiceLocator()->get(
                'usarugbystats_competition_union_service'
            ));
        }

        return $this->unionService;
    }

    public function setUnionService(UnionService $s)
    {
        $this->unionService = $s;

        return $this;
    }
}
