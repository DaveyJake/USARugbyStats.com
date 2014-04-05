<?php

namespace UsaRugbyStats\CompetitionAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TeamAdminController extends AbstractActionController
{

    public function listAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/list');
        return $vm;
    }

    public function createAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/create');
        return $vm;
    }

    public function editAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/edit');
        return $vm;
    }

    public function removeAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/competition-admin/team-admin/remove');
        return $vm;
    }

}
