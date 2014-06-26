<?php
namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\LocationService;
use UsaRugbyStats\Competition\Entity\Location;

class LocationAdminController extends AbstractActionController
{
    protected $locationService;

    public function listAction()
    {
        $svc = $this->getLocationService();

        $paginator = $svc->fetchAll();
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-admin/location-admin/list');

        return $vm;
    }

    public function createAction()
    {
        $form = $this->getLocationService()->getCreateForm();
        if ( $this->getRequest()->isPost() ) {
            $result = $this->getLocationService()->create($this->getRequest()->getPost()->toArray());
            if ($result instanceof Location) {
                $this->flashMessenger()->addSuccessMessage('The location was created successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_locationadmin/edit', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/location-admin/create');

        return $vm;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getLocationService()->findByID($id);
        if (! $entity instanceof Location) {
            throw new \RuntimeException('No location with the specified identifier!');
        }

        $form = $this->getLocationService()->getUpdateForm();

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getLocationService()->update($entity, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Location) {
                $this->flashMessenger()->addSuccessMessage('The location was updated successfully!');

                return $this->redirect()->toRoute('zfcadmin/usarugbystats_locationadmin/edit', ['id' => $result->getId()]);
            }
        } else {
            $form->bind($entity);
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-admin/location-admin/edit');

        return $vm;
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getLocationService()->findByID($id);
        if (! $entity instanceof Location) {
            throw new \RuntimeException('No location with the specified identifier!');
        }

        if ( $this->getRequest()->isPost() && $this->params()->fromPost('confirmed') == 'Y' ) {
            $this->getLocationService()->remove($entity);
            $this->flashMessenger()->addSuccessMessage('The location was removed successfully!');

            return $this->redirect()->toRoute('zfcadmin/usarugbystats_locationadmin/list');
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $entity);
        $vm->setTemplate('usa-rugby-stats/competition-admin/location-admin/remove');

        return $vm;
    }

    public function getLocationService()
    {
        if (! $this->locationService instanceof LocationService) {
            $this->setLocationService($this->getServiceLocator()->get(
                'usarugbystats_competition_location_service'
            ));
        }

        return $this->locationService;
    }

    public function setLocationService(LocationService $s)
    {
        $this->locationService = $s;

        return $this;
    }
}
