<?php
namespace UsaRugbyStats\CompetitionFrontendEmbed\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionStandingsServiceTrait;

class CompetitionScheduleController extends AbstractActionController
{
    use CompetitionServiceTrait;
    use CompetitionStandingsServiceTrait;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $competition = $this->getCompetitionService()->findByID($id);
        if (! $competition instanceof Competition) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setTemplate('usa-rugby-stats/competition-frontend-embed/competition/schedule');

        return $vm;
    }

}
