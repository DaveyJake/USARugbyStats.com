<?php
namespace UsaRugbyStats\Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('usa-rugby-stats/application/index/index');
        return $vm;        
    }
}
