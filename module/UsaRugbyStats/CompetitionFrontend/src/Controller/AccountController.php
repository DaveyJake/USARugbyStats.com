<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AccountController extends AbstractActionController
{
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }
        return $this->redirect()->toRoute('usarugbystats_frontend_player', ['id' => $this->user()->getId()]);
    }
}