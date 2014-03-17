<?php
namespace UsaRugbyStats\AccountAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Controller which user create/edit calls via XHR to render RoleAssignment templates
 */
class TemplateRendererController extends AbstractActionController
{
    public function renderAction()
    {
        $vm = new JsonModel();

        if ( ! $this->getRequest()->isPost() || ! $this->getRequest()->isXmlHttpRequest() ) {
            return $vm->setVariables(array('error' => 'Malformed Request'));
        }

        $collection = $this->getServiceLocator()->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement');
        
        $template = $this->params()->fromPost('template');
        
        $fieldset = null;
        foreach ( $collection->getTargetElement() as $item ) {
            if ( $item->getName() == $template ) {
                $fieldset = $item;
                continue;
            }
        }
        if ( empty($fieldset) ) {
            return $vm->setVariables(array('error' => 'Unknown Template!'));
        }
        if ( ! preg_match('{^UsaRugbyStats\\\\AccountAdmin\\\\Form\\\\Rbac\\\\RoleAssignment\\\\(.+)Fieldset$}is', get_class($fieldset), $matches) ) {
            return $vm->setVariables(array('error' => 'Unknown Template!'));
        }
        $templateFile = 'usa-rugby-stats/account-admin/role-assignments/' . strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $matches[1]));

        $fieldset->setName($this->params()->fromPost('namePrefix'));
        $fieldset->prepareElement(new \Zend\Form\Form());

        $hvm = new ViewModel(array(
            'fieldset' => $fieldset, 
            'isTemplate' => true, 
            'index' => $this->params()->fromPost('index'),
            'type' => $matches[1],
        ));
        $hvm->setTemplate($templateFile);

        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        return $vm->setVariables(array('template' => $viewRender->render($hvm)));
    }
}