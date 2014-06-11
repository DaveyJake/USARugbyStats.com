<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use UsaRugbyStats\Application\Entity\AccountInterface;

class PlayerController extends AbstractActionController
{
    protected $userService;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        $player = $this->getUserService()->getUserMapper()->findById($id);
        if ( ! $player instanceof AccountInterface ) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        $vm = new ViewModel();
        $vm->setVariable('player', $player);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/player/index');

        return $vm;
    }

    public function getUserService()
    {
        if (! $this->userService instanceof UserService) {
            $this->setUserService($this->getServiceLocator()->get('zfcuser_user_service'));
        }

        return $this->userService;
    }

    public function setUserService(UserService $s)
    {
        $this->userService = $s;

        return $this;
    }
}
