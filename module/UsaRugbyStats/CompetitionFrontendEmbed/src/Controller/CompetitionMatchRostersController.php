<?php
namespace UsaRugbyStats\CompetitionFrontendEmbed\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamPlayerFieldset;

class CompetitionMatchRostersController extends AbstractActionController
{
    use CompetitionServiceTrait;
    use CompetitionMatchServiceTrait;

    public function indexAction()
    {
        $cid = $this->params()->fromRoute('cid');
        if ( ! \Zend\Validator\StaticValidator::execute($cid, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $competition = $this->getCompetitionService()->findByID($cid);
        if (! $competition instanceof Competition) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $mid = $this->params()->fromRoute('mid');
        if ( ! \Zend\Validator\StaticValidator::execute($mid, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Match ID specified!');
        }

        $match = $this->getCompetitionMatchService()->findByID($mid);
        if (! $match instanceof Match || $match->getCompetition()->getId() != $cid) {
            throw new \InvalidArgumentException('Invalid Match ID specified!');
        }

        $positions = MatchTeamPlayerFieldset::$positions[$competition->getVariant()];

        $vm = new ViewModel();
        $vm->setVariable('match', $match);
        $vm->setVariable('positions', $positions);
        $vm->setVariable('forcePrint', $this->params()->fromQuery('forcePrint', '0') === '1');
        $vm->setTemplate('usa-rugby-stats/competition-frontend-embed/competition/match/rosters');

        return $vm;
    }

}
