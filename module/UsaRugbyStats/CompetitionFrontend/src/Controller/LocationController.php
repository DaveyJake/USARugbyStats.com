<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcRbac\Exception\UnauthorizedException;
use UsaRugbyStats\Competition\Entity\Location;
use UsaRugbyStats\Competition\Service\LocationService;
use UsaRugbyStats\Competition\Service\Competition\MatchService;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class LocationController extends AbstractActionController
{
    protected $locationService;
    protected $matchService;

    public function listAction()
    {
        if ( ! $this->isGranted('competition.location.list') ) {
            throw new UnauthorizedException();
        }

        $paginator = $this->getLocationService()->fetchAll();
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/location/list');

        return $vm;
    }

    public function searchAction()
    {
        if ( ! $this->isGranted('competition.location.list') ) {
            throw new UnauthorizedException();
        }

        $q = trim($this->params()->fromQuery('q'), ' %');
        if ( empty($q) ) {
            return $this->redirect()->toRoute('usarugbystats_frontend_location');
        }

        $svc = $this->getLocationService();

        $paginator = $svc->findAllBySearchQuery($q);
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        $vm = new ViewModel();
        $vm->setVariable('paginator', $paginator);
        $vm->setVariable('q', $q);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/location/search/list');

        return $vm;
    }

    public function viewAction()
    {
        $location = $this->getLocationEntityFromRoute();

        $repository = $this->getMatchService()->getRepository();
        $collection = new ArrayCollection($repository->findBy(['location' => $location], ['date' => 'ASC']));
        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $collection->partition(function ($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('location', $location);
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/location/view');

        return $vm;
    }

    public function createAction()
    {
        $service = $this->getLocationService();

        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->entity = new Location();
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();

            $result = $this->getLocationService()->create($data);
            if ($result instanceof Location) {
                $this->flashMessenger()->addSuccessMessage('The location was created successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_location/update', [
                    'id' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $session->entity);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/location/create');

        return $vm;
    }

    public function updateAction()
    {
        $location = $this->getLocationEntityFromRoute();
        if ( ! $this->isGranted('competition.location.update', $location) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getLocationService();
        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->entity = $location;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost()->toArray();

            $result = $this->getLocationService()->update($location, $data);
            if ($result instanceof Location) {
                $this->flashMessenger()->addSuccessMessage('The location was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_location/update', [
                    'id' => $result->getId(),
                ]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $session->entity);
        $vm->setVariable('form', $session->form);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/location/update');

        return $vm;
    }

    protected function getLocationEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        $entity = $this->getLocationService()->findByID($id);
        if (! $entity instanceof Location) {
            throw new \RuntimeException('No location with the specified identifier!');
        }

        return $entity;
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
