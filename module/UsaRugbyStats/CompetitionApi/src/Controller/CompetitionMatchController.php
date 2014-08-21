<?php
namespace UsaRugbyStats\CompetitionApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Zend\Form\FormInterface;

class CompetitionMatchController extends AbstractRestfulController
{
    use CompetitionServiceTrait;
    use CompetitionMatchServiceTrait;

    public function create($data)
    {
        $competition = $this->getCompetitionEntityFromRoute();
        if ($competition instanceof ApiProblem) {
            return new ApiProblemResponse($competition);
        }

        if ( ! $this->isGranted('competition.competition.match.create', $competition) ) {
            return new ApiProblemResponse(new ApiProblem(403, 'Not authorized to create matches!'));
        }

        $service = $this->getCompetitionMatchService();

        $session = $service->startSession();
        $session->form = $service->getCreateForm();
        $session->competition = $competition;
        $session->entity = new Match();
        $service->prepare();

        $data = $this->getRequest()->getPost()->toArray();
        $data['match']['competition'] = $competition->getId();
        //@TODO why is this necessay here?
        $data['match']['teams']['H']['type'] = 'H';
        $data['match']['teams']['A']['type'] = 'A';

        $result = $service->create($data);
        if ($result instanceof Match) {
            return new JsonModel([
                'data' => $this->extractMatch($result)
            ]);
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
        $match = $this->getCompetitionMatchEntityFromRoute();
        if ($match instanceof ApiProblem) {
            return new ApiProblemResponse($match);
        }

        return new JsonModel($this->extractMatch($match));
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

    protected function getCompetitionEntityFromRoute()
    {
        $id = $this->params()->fromRoute('cid');
        $comp = $this->getCompetitionService()->findByID($id);
        if (! $comp instanceof Competition) {
            return new ApiProblem(404, 'Competition not found!');
        }

        return $comp;
    }

    protected function getCompetitionMatchEntityFromRoute()
    {
        $comp = $this->getCompetitionEntityFromRoute();
        if ($comp instanceof ApiProblem) {
            return $comp;
        }

        $id = $this->params()->fromRoute('id');
        $match = $this->getCompetitionMatchService()->findByID($id);
        if (! $match instanceof Match || $match->getCompetition()->getId() != $comp->getId()) {
            return new ApiProblem(404, 'Match not found!');
        }

        return $match;
    }

    protected function extractMatch(Match $m)
    {
        $form = $this->getServiceLocator()->get('usarugbystats_competition_competition_match_updateform');
        $form->bind($m);
        $form->isValid();

        return $form->getData(FormInterface::VALUES_AS_ARRAY);
    }
}
