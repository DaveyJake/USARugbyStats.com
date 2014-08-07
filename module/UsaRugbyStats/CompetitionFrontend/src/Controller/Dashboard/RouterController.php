<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Dashboard;

use Zend\Mvc\Controller\AbstractActionController;

class RouterController extends AbstractActionController
{
    public function indexAction()
    {
        $user = $this->zfcUserAuthentication()->getIdentity();
        $userRoles = $user->getRoleAssignments();
        if (count($userRoles) == 0) {
            throw new \RuntimeException('Your account does not have any assigned roles.  Please contact technical support for assistance.');
        }

        $controllerPattern = 'usarugbystats_competition-frontend_dashboard_%s_controller';

        // Display the correct dashboard based on user's role
        foreach ($userRoles as $roleAssignment) {
            $role = $roleAssignment->getRole();
            switch ( $role->getName() ) {
                case 'super_admin':
                    return $this->redirect()->toRoute('zfcadmin');
                case 'union_admin':
                    $managedUnions = $roleAssignment->getManagedUnions();
                    if ( $managedUnions->count() == 1 ) {
                        return $this->redirect()->toRoute(
                            'usarugbystats_frontend_union',
                            ['id' => $managedUnions->current()->getId()]
                        );
                    }
                    $controllerName = sprintf($controllerPattern, 'union-admin');

                    return $this->forward()->dispatch($controllerName);
                case 'competition_admin':
                    $managedComps = $roleAssignment->getManagedCompetitions();
                    if ( $managedComps->count() == 1 ) {
                        return $this->redirect()->toRoute(
                            'usarugbystats_frontend_competition',
                            ['id' => $managedComps->current()->getId()]
                        );
                    }
                    $controllerName = sprintf($controllerPattern, 'competition-admin');

                    return $this->forward()->dispatch($controllerName);
                case 'team_admin':
                    $managedTeams = $roleAssignment->getManagedTeams();
                    if ( $managedTeams->count() == 1 ) {
                        return $this->redirect()->toRoute(
                            'usarugbystats_frontend_team',
                            ['id' => $managedTeams->current()->getId()]
                        );
                    }
                    $controllerName = sprintf($controllerPattern, 'team-admin');

                    return $this->forward()->dispatch($controllerName);
            }
        }

        return $this->redirect()->toRoute('usarugbystats_frontend_player', ['id' => $user->getId()]);
    }
}
