<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Competition;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\Competition\MatchService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchController extends AbstractActionController
{
    protected $matchService;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('mid');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Match ID specified!');
        }

        $match = $this->getMatchService()->findByID($id);
        if ( ! $match instanceof Match ) {
            throw new \InvalidArgumentException('Invalid Match ID specified!');
        }

        $vm = new ViewModel();
        $vm->setVariable('match', $match);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/match/index');

        return $vm;
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
