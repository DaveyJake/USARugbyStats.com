<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;
use ZfcRbac\Exception\UnauthorizedException;

class CompetitionController extends AbstractActionController
{
    protected $competitionService;

    public function indexAction()
    {
        $competition = $this->getCompetition();

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/index');

        return $vm;
    }

    public function updateAction()
    {
        $competition = $this->getCompetition();

        if ( ! $this->isGranted('competition.competition.update', $competition) ) {
            throw new UnauthorizedException();
        }

        $form = $this->getCompetitionService()->getUpdateForm();

        if ( $this->getRequest()->isPost() ) {
            $result = $this->getCompetitionService()->update($competition, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_competition/update', ['id' => $result->getId()]);
            }
        } else {
            $form->bind($competition);
        }

        $vm = new ViewModel();
        $vm->setVariable('entity', $competition);
        $vm->setVariable('form', $form);
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/update');

        return $vm;
    }

    protected function getCompetition()
    {
        $id = $this->params()->fromRoute('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        $competition = $this->getCompetitionService()->findByID($id);
        if (! $competition instanceof Competition) {
            throw new \InvalidArgumentException('Invalid Competition ID specified!');
        }

        return $competition;
    }

    public function getCompetitionService()
    {
        if (! $this->competitionService instanceof CompetitionService) {
            $this->setCompetitionService($this->getServiceLocator()->get(
                'usarugbystats_competition_competition_service'
            ));
        }

        return $this->competitionService;
    }

    public function setCompetitionService(CompetitionService $s)
    {
        $this->competitionService = $s;

        return $this;
    }

}
