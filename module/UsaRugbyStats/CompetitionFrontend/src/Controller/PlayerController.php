<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Account\Traits\UserServiceTrait;
use UsaRugbyStats\Competition\Traits\TeamServiceTrait;
use UsaRugbyStats\Competition\Traits\PlayerStatisticsServiceTrait;
use LdcUserProfile\Service\ProfileService;
use LdcUserProfile\Options\ModuleOptions;
use Zend\Stdlib\ResponseInterface;
use ZfcRbac\Exception\UnauthorizedException;

class PlayerController extends AbstractActionController
{
    use UserServiceTrait;
    use TeamServiceTrait;
    use PlayerStatisticsServiceTrait;

    protected $profileService;
    protected $moduleOptions;

    public function indexAction()
    {
        $player = $this->getPlayerEntityFromRoute();

        $teams = $this->getTeamService()->getRepository()->findAllForPlayer($player);
        $statistics = $this->getPlayerStatisticsService()->getStatisticsFor($player);

        $vm = new ViewModel();
        $vm->setVariable('player', $player);
        $vm->setVariable('teams', $teams);
        $vm->setVariable('statistics', $statistics);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/player/index');

        return $vm;
    }

    public function updateAction()
    {
        $player = $this->getPlayerEntityFromRoute();
        if ( ! $this->isGranted('account.profile', $player) ) {
            throw new UnauthorizedException('You are not authorized to edit this player\'s profile');
        }

        $selfurl = $this->url()->fromRoute('usarugbystats_frontend_player/update', ['id' => $player->getId()]);

        $form = $this->getService()->constructFormForUser($player);
        $form->setAttribute('action', $selfurl);

        $vm = new ViewModel();
        $vm->setVariable('player', $player);
        $vm->setVariable('form', $form);
        $vm->setVariable('options', $this->getModuleOptions());
        $vm->setTemplate('usa-rugby-stats/competition-frontend/player/update');

        $prg = $this->fileprg($form, $selfurl, true);
        if ($prg instanceof ResponseInterface) {
            return $prg;
        } elseif ($prg === false) {
            return $vm;
        }

        $fm = $this->flashMessenger()->setNamespace('ldc-user-profile');

        if (! $this->getService()->validate($form, $prg)) {
            $fm->addErrorMessage('One or more of the values you provided is invalid.');

            return $vm;
        }

        if (! $this->getService()->save($form->getData())) {
            $fm->addErrorMessage('There was a problem saving your profile update.');

            return $vm;
        }

        $fm->addSuccessMessage('Profile updated successfully!');

        return $this->redirect()->toUrl($selfurl);
    }

    protected function getPlayerEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        $player = $this->getUserService()->getUserMapper()->findById($id);
        if (! $player instanceof AccountInterface) {
            throw new \InvalidArgumentException('Invalid Player ID specified!');
        }

        return $player;
    }

    public function setService(ProfileService $svc)
    {
        $this->profileService = $svc;

        return $this;
    }

    public function getService()
    {
        if (! $this->profileService instanceof ProfileService) {
            $this->profileService = $this->getServiceLocator()->get(
                'ldc-user-profile_service'
            );
        }

        return $this->profileService;
    }

    public function setModuleOptions(ModuleOptions $obj)
    {
        $this->moduleOptions = $obj;

        return $this;
    }

    public function getModuleOptions()
    {
        if (! $this->moduleOptions instanceof ModuleOptions) {
            $this->moduleOptions = $this->getServiceLocator()->get(
                'ldc-user-profile_module_options'
            );
        }

        return $this->moduleOptions;
    }
}
