<?php
namespace UsaRugbyStats\Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Headers;

class SessionController extends AbstractActionController
{
    public function sidAction()
    {
        if ( ! $this->getRequest()->isXmlHttpRequest() ) {
            return $this->getResponse()->setStatusCode(403);
        }

        $this->getResponse()->getHeaders()->addHeaders(Headers::FromString('Content-type: application/json'));
        return $this->getResponse()->setContent(json_encode([
            'sid' => session_id()
        ]));
    }
}
