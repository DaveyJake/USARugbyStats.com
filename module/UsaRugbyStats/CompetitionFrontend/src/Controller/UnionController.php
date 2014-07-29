<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\UnionService;
use UsaRugbyStats\Competition\Entity\Union;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use ZfcRbac\Exception\UnauthorizedException;

class UnionController extends AbstractActionController
{
    use CompetitionMatchServiceTrait;

    protected $unionService;

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        $union = $this->getUnionService()->findByID($id);
        if (! $union instanceof Union) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        $repository = $this->getCompetitionMatchService()->getRepository();

        $now = new \DateTime();
        list($upcomingMatches, $pastMatches) = $repository->findAllForUnion($union)->partition(function ($key, Match $m) use ($now) {
            return $m->getDate() >= $now;
        });

        $vm = new ViewModel();
        $vm->setVariable('union', $union);
        $vm->setVariable('upcomingMatches', $upcomingMatches);
        $vm->setVariable('pastMatches', $pastMatches);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/union/index');

        return $vm;
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        $union = $this->getUnionService()->findByID($id);
        if (! $union instanceof Union) {
            throw new \InvalidArgumentException('Invalid Union ID specified!');
        }

        if ( ! $this->isGranted('competition.union.update', $union) ) {
            throw new UnauthorizedException();
        }

        $form = $this->getUnionService()->getUpdateForm();

        if ( $this->getRequest()->isPost() ) {
            $formData = $this->getRequest()->getPost()->toArray();
            $result = $this->getUnionService()->update($union, $formData);
            if ($result instanceof Union) {
                $this->flashMessenger()->addSuccessMessage('The union was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_union/update', ['id' => $result->getId()]);
            }
        } else {
            $form->bind($union);
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $union);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/union/update');

        return $vm;
    }

    public function getUnionService()
    {
        if (! $this->unionService instanceof UnionService) {
            $this->setUnionService($this->getServiceLocator()->get(
                'usarugbystats_competition_union_service'
            ));
        }

        return $this->unionService;
    }

    public function setUnionService(UnionService $s)
    {
        $this->unionService = $s;

        return $this;
    }
}
