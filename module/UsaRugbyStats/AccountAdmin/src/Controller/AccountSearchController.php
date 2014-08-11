<?php
namespace UsaRugbyStats\AccountAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator;
use ZfcUser\Mapper\UserInterface;
use ZfcUser\Options\ModuleOptions as ZfcUserModuleOptions;
use ZfcUserAdmin\Options\ModuleOptions;

class AccountSearchController extends AbstractActionController
{
    protected $options, $userMapper;
    protected $zfcUserOptions;

    public function searchAction()
    {
        $q = trim($this->params()->fromQuery('q'), ' %');
        if ( empty($q) ) {
            return $this->redirect()->toRoute('zfcadmin/zfcuseradmin/list');
        }

        $userMapper = $this->getUserMapper();
        $users = $userMapper->findAllBySearchQuery($q);
        if (is_array($users)) {
            $paginator = new Paginator\Paginator(new Paginator\Adapter\ArrayAdapter($users));
        } else {
            $paginator = $users;
        }

        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));

        $vm = new ViewModel(array(
            'users' => $paginator,
            'userlistElements' => $this->getOptions()->getUserListElements(),
            'q' => $q,
        ));

        $vm->setTemplate('usa-rugby-stats/account-admin/account-search/list');

        return $vm;
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('zfcuseradmin_module_options'));
        }

        return $this->options;
    }

    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }

        return $this->userMapper;
    }

    public function setUserMapper(UserInterface $userMapper)
    {
        $this->userMapper = $userMapper;

        return $this;
    }

    public function getAdminUserService()
    {
        if (null === $this->adminUserService) {
            $this->adminUserService = $this->getServiceLocator()->get('zfcuseradmin_user_service');
        }

        return $this->adminUserService;
    }

    public function setAdminUserService($service)
    {
        $this->adminUserService = $service;

        return $this;
    }

    public function setZfcUserOptions(ZfcUserModuleOptions $options)
    {
        $this->zfcUserOptions = $options;

        return $this;
    }

    /**
     * @return \ZfcUser\Options\ModuleOptions
     */
    public function getZfcUserOptions()
    {
        if (!$this->zfcUserOptions instanceof ZfcUserModuleOptions) {
            $this->setZfcUserOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }

        return $this->zfcUserOptions;
    }

}
