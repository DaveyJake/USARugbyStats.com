<?php
namespace UsaRugbyStats\Account\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\Account\Traits\UserServiceTrait;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use UsaRugbyStats\Application\Entity\AccountInterface;

class AccountApiController extends AbstractRestfulController
{
    use UserServiceTrait;

    public function create($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function delete($id)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function deleteList()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function get($id)
    {
        $acct = $this->getUserService()->getUserMapper()->findById($id);
        if (! $acct instanceof AccountInterface) {
            return new ApiProblemResponse(new ApiProblem(404, 'User Not Found!'));
        }

        return new JsonModel($this->extractUser($acct));
    }

    public function getList()
    {
        $q = trim($this->getRequest()->getQuery('q'));
        if ( strlen($q) < 3 && ! preg_match('{^#}', $q) ) {
            return new ApiProblemResponse(new ApiProblem(400, 'Search string too short!'));
        }

        if ( preg_match('{^#([0-9]+)$}', $q, $matches ) ) {
            $acct = $this->getUserService()->getUserMapper()->findById($matches[1]);
            if (! $acct instanceof AccountInterface) {
                return new ApiProblemResponse(new ApiProblem(404, 'User Not Found!'));
            }

            return new JsonModel([$this->extractUser($acct)]);
        }

        $resultset = $this->getUserService()->getUserMapper()->findAllBySearchQuery($q);
        if (count($resultset) == 0) {
            return new JsonModel([]);
        }

        $dehydrated = array();
        foreach ($resultset as $item) {
            $dehydrated[] = $this->extractUser($item);
        }

        return new JsonModel($dehydrated);
    }

    public function head($id = null)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function options()
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patch($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function patchList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function replaceList($data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    public function update($id, $data)
    {
        return new ApiProblemResponse(new ApiProblem(405, null));
    }

    protected function getUserEntityFromRoute()
    {
        $id = $this->params()->fromRoute('id');
        $user = $this->getUserService()->getUserMapper()->findById($id);
        if (! $user instanceof AccountInterface) {
            return new ApiProblem(404, 'User not found!');
        }

        return $user;
    }

    protected function extractUser($user)
    {
        return array(
            'id' => $user->getId(),
            'display_name' => $user->getDisplayName(),
        );
    }

}
