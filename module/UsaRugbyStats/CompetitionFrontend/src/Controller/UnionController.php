<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\UnionService;
use UsaRugbyStats\Competition\Entity\Union;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class UnionController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;

    protected $unionService;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        $union = $this->getUnionService()->findByID($id);
        if ( ! $union instanceof Union ) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        $repository = $this->getCompetitionMatchService()->getMatchRepository();

        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $repository->findAllForUnion($union)->partition(function($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('union', $union);
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/union/index');

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
