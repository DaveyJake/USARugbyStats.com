<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Account\Traits\UserServiceTrait;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\Competition\Traits\PlayerStatisticsServiceTrait;

class PlayerController extends AbstractActionController
{
    use UserServiceTrait;
    use TeamServiceTrait;
    use PlayerStatisticsServiceTrait;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        $player = $this->getUserService()->getUserMapper()->findById($id);
        if (! $player instanceof AccountInterface) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        $teams = $this->getTeamService()->getRepository()->findAllForPlayer($player);
        $statistics = $this->getPlayerStatisticsService()->getStatisticsFor($player);

        $vm = new ViewModel();
        $vm->setVariable('player', $player);
        $vm->setVariable('teams', $teams);
        $vm->setVariable('statistics', $statistics);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/player/index');

        return $vm;
    }

}
