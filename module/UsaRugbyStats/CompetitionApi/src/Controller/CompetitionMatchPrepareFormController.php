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
        if ($match instanceof ApiProblem) {
            return new ApiProblemResponse($match);
        }

        $svc = $this->getCompetitionMatchService();
        $session = $svc->startSession();
        $session->form = $svc->getCreateForm();
        $session->entity = $match;
        $session->competition = $match->getCompetition();
        $svc->prepare();

        $session->form->prepare();
        $fs = $session->form->get('match');

        $eventTypes = [];
        $events = $fs->get('teams')->get('H')->get('events')->getTargetElement();
        foreach ($events as $key => $fsEvent) {
            switch ($key) {
                case 'sub':
                    $name = 'Substitution';
                    break;
                case 'card':
                    $name = 'Card';
                    break;
                case 'score':
                    $name = 'Score';
                    break;
            }
            $eventTypes[$key] = [
                'name' => $name,
                'options' => $this->processValueOptions($fsEvent->get('type')->getValueOptions()),
                'optionLookup' => $fsEvent->get('type')->getValueOptions(),
            ];
        }

        return new JsonModel([
            'feature_flags' => $session->form->getFeatureFlags()->toArray(),
            'datasets' => [
                'locations' => $this->processValueOptions($fs->get('location')->getValueOptions()),
                'teams' => $this->processValueOptions($fs->get('teams')->getTargetElement()->get('team')->getValueOptions()),
                'players' => [
                    'H' => $this->processValueOptions($fs->get('teams')->get('H')->get('players')->getTargetElement()->get('player')->getValueOptions()),
                    'A' => $this->processValueOptions($fs->get('teams')->get('A')->get('players')->getTargetElement()->get('player')->getValueOptions()),
                ],
                'event' => [
                    'types' => $eventTypes,
                ]
            ],
        ]);
    }

    protected function processValueOptions($vo)
    {
        $output = [];
        foreach ($vo as $key => $entry) {
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
            if ($require_match_id === false) {
                return null;
            }

            return new ApiProblem(404, 'Match not found!');
        }

        $comp = $this->getCompetitionEntityFromRoute();
        if (! $comp instanceof Competition) {
            return $comp;
        }

        if ( $comp->getId() !== $match->getCompetition()->getId() ) {
            return new ApiProblem(404, 'Match not found!');
        }

        return $match;
    }
}
