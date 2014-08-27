<?php
namespace UsaRugbyStats\CompetitionFrontendEmbed\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;

class TeamScheduleController extends AbstractActionController
{
    use TeamServiceTrait;
    use CompetitionMatchServiceTrait;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('tid');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        $team = $this->getTeamService()->findByID($id);
        if (! $team instanceof Team) {
            throw new \InvalidArgumentException('Invalid Team ID specified!');
        }

        $vm = new ViewModel();
        $vm->setVariable('team', $team);
        $vm->setVariable('matches', $this->getCompetitionMatchService()->findAllForTeam($team));
        $vm->setTemplate('usa-rugby-stats/competition-frontend-embed/team/schedule');

        return $vm;
    }

}
