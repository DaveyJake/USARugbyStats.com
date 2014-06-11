<?php
namespace UsaRugbyStats\CompetitionFrontend\Controller\Dashboard;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RouterController extends AbstractActionController
{
    public function indexAction()
    {
        $user = $this->zfcUserAuthentication()->getIdentity();
        $userRoles = $user->getRoles();
        if (count($userRoles) == 0) {
            throw new \RuntimeException('Your account does not have any assigned roles.  Please contact technical support for assistance.');
        }

        $controllerPattern = 'usarugbystats_competition-frontend_dashboard_%s_controller';

        // Display the correct dashboard based on user's role
        // @TODO Users with multiple roles should be able to choose which one is active
        $role = array_pop($userRoles);
        switch ( $role->getName() )
        {
            case 'super_admin':
                return $this->redirect()->toRoute('zfcadmin');
            case 'union_admin':
                $controllerName = sprintf($controllerPattern, 'union-admin');
                break;
            case 'competition_admin':
                $controllerName = sprintf($controllerPattern, 'competition-admin');
                break;
            case 'team_admin':
                $controllerName = sprintf($controllerPattern, 'team-admin');
                break;
        	case 'member':
        	default:
        	    return $this->redirect()->toRoute('usarugbystats_frontend_player', ['id' => $user->getId()]);
        }

        return $this->forward()->dispatch($controllerName);
    }
}
