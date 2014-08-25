<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UsaRugbyStats\Competition\Entity\Competition;
use ZfcRbac\Exception\UnauthorizedException;
use UsaRugbyStats\Competition\Traits\CompetitionServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionStandingsServiceTrait;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;

class CompetitionController extends AbstractActionController
{
    use CompetitionServiceTrait;
    use CompetitionStandingsServiceTrait;
    use CompetitionMatchServiceTrait;

    public function indexAction()
    {
        $competition = $this->getCompetition();

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('standings', $this->getCompetitionStandingsService()->getStandingsFor($competition));
        $vm->setTemplate('usa-rugby-stats/competition-frontend/competition/index');

        return $vm;
    }

    public function updateAction()
    {
        $competition = $this->getCompetition();

        if ( ! $this->isGranted('competition.competition.update', $competition) ) {
            throw new UnauthorizedException();
        }

        $service = $this->getCompetitionService();

        $session = $service->startSession();
        $session->form = $service->getUpdateForm();
        $session->entity = $competition;
        $service->prepare();

        if ( $this->getRequest()->isPost() ) {

            // Enforce RBAC permissions
            $vg = $session->form->getValidationGroup();
            if ( ! $this->isGranted('competition.competition.update.details', $competition) ) {
                $vg = ['competition' => ['divisions' => $vg['competition']['divisions']]];
            }
            if ( ! $this->isGranted('competition.competition.update.divisions', $competition) ) {
                unset($vg['competition']['divisions']);
            }
            if ( empty($vg['competition']) ) {
                throw new UnauthorizedException();
            }
            $session->form->setValidationGroup($vg);

            $result = $this->getCompetitionService()->update($competition, $this->getRequest()->getPost()->toArray());
            if ($result instanceof Competition) {
                $this->flashMessenger()->addSuccessMessage('The competition was updated successfully!');

                return $this->redirect()->toRoute('usarugbystats_frontend_competition/update', ['id' => $result->getId()]);
            }
        }

        $vm = new ViewModel();
        $vm->setVariable('competition', $competition);
        $vm->setVariable('form', $session->form);
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

}
