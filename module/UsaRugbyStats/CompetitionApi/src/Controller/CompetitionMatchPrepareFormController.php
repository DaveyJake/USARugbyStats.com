<?php
namespace UsaRugbyStats\CompetitionApi\Controller;

use Zend\View\Model\JsonModel;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Zend\Mvc\Controller\AbstractActionController;

class CompetitionMatchPrepareFormController extends AbstractActionController
{
    use CompetitionServiceTrait;
    use CompetitionMatchServiceTrait;

    public function prepareFormAction()
    {
        $match = $this->getCompetitionMatchEntityFromRoute(false);
        if ( $match instanceof ApiProblem ) {
            return new ApiProblemResponse($match);
        }

        $svc = $this->getCompetitionMatchService();
        $session = $svc->startSession();
        $session->form = $svc->getCreateForm();
        $session->entity = new Match();
        $svc->prepare();

        $session->form->prepare();
        $fs = $session->form->get('match');

        return new JsonModel([
            'feature_flags' => $session->form->getFeatureFlags()->toArray(),
            'datasets' => [
                'locations' => $this->processValueOptions($fs->get('location')->getValueOptions()),
                'teams' => $this->processValueOptions($fs->get('teams')->getTargetElement()->get('team')->getValueOptions()),
            ]
        ]);
    }

    protected function processValueOptions($vo)
    {
        $output = [];
        foreach ( $vo as $key => $entry ) {
            if (is_array($entry)) {
                $label = $entry['label'];
                $value = $entry['value'];
            } else {
                $label = $entry;
                $value = $key;
            }

            if ( empty($value) && $value !== '0' ) {
                continue;
            }
            $output[] = ['label' => $label, 'value' => $value];
        }
        return $output;
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

    protected function getCompetitionMatchEntityFromRoute($require_match_id = true)
    {
        $id = $this->params()->fromRoute('id');
        if ( empty($id) && $require_match_id === false ) {
            return null;
        }

        $match = $this->getCompetitionMatchService()->findByID($id);
        if (! $match instanceof Match) {
            if ( $require_match_id === false ) {
                return null;
            }
            return new ApiProblem(404, 'Match not found!');
        }

        $comp = $this->getCompetitionEntityFromRoute();
        if (! $comp instanceof Competition ) {
            return $comp;
        }

        if ( $comp->getId() !== $match->getCompetition()->getId() ) {
            return new ApiProblem(404, 'Match not found!');
        }

        return $match;
    }
}
