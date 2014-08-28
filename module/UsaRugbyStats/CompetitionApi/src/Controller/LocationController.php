<?php
namespace UsaRugbyStats\CompetitionApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\Competition\Traits\LocationServiceTrait;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use UsaRugbyStats\Competition\Entity\Location;
use Zend\Form\FormInterface;

class LocationController extends AbstractRestfulController
{
    use LocationServiceTrait;

    public function create($data)
    {
        if ( ! $this->isGranted('competition.location.create') ) {
            return new ApiProblemResponse(new ApiProblem(403, 'Not authorized to create locations!'));
        }

        $service = $this->getLocationService();
        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->entity = new Location();
        $service->prepare();

        $data = $this->getRequest()->getPost()->toArray();

        $result = $service->create($data);
        if ($result instanceof Location) {
            return new JsonModel($this->extractLocation($result));
        }

        return new ApiProblemResponse(
            new ApiProblem(422, 'Validation failed', null, null, ['validation_messages' => $session->form->getMessages()])
        );
    }

    public function delete($id)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function deleteList()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function get($id)
    {
        $location = $this->getLocationEntityFromRoute();
        if ($location instanceof ApiProblem) {
            return new ApiProblemResponse($location);
        }

        return new JsonModel($this->extractLocation($location));
    }

    public function getList()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function head($id = null)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function options()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patch($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patchList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function replaceList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function update($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    protected function getLocationEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        $comp = $this->getLocationService()->findByID($id);
        if (! $comp instanceof Location) {
            return new ApiProblem(404, 'Location not found!');
        }

        return $comp;
    }

    protected function extractLocation($loc)
    {
        $service = $this->getLocationService();
        $session = $service->startSession();
        $session->form = $this->getServiceLocator()->get('usarugbystats_competition_location_updateform');
        $session->entity = $loc;
        $service->prepare();

        $session->form->isValid();
        $data = $session->form->getData(FormInterface::VALUES_AS_ARRAY);
        unset($data['submit']);

        return $data;
    }

}
