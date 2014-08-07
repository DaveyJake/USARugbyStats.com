<?php
namespace UsaRugbyStats\Account\Fixtures\Testing;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Account\Entity\Account;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class TestAccountsFixture implements FixtureInterface, DependentFixtureInterface,  ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function load(ObjectManager $manager)
    {
        echo "\nCreating Testing Accounts...\n";

        $svc  = $this->getServiceLocator()->get('UsaRugbyStats\AccountAdmin\Service\UserService');

        foreach ($this->accountData as $acct) {
            echo " - {$acct['username']}";

            $acct['passwordVerify'] = $acct['password'];

            echo "\n";

            $form = $this->getServiceLocator()->get('zfcuseradmin_createuser_form');
            $entity = $svc->create($form, $acct);
            if (! $entity instanceof Account) {
                echo "ERROR: Failed to create account: " . $acct['username'] . "\n";
                continue;
            }
            unset($form, $entity);
        }
    }

    public function getDependencies()
    {
        return [
            'UsaRugbyStats\Account\Fixtures\Common\RbacRoleFixture',
            'UsaRugbyStats\Competition\Fixtures\Testing\TestTeamsFixture',
            'UsaRugbyStats\Competition\Fixtures\Testing\TestUnionsFixture',
            'UsaRugbyStats\Competition\Fixtures\Testing\TestCompetitionsFixture',
        ];
    }

    protected $accountData = array(
        [ 'username' => 'superadmin', 'email' => 'urssuperadmin@lundrigan.ca', 'display_name' => 'Super Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'super_admin' ]] ],
        [ 'username' => 'teamadmin', 'email' => 'ursteamadmin@lundrigan.ca', 'display_name' => 'Team Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'team_admin' ]] ],
        [ 'username' => 'teamadmin_single', 'email' => 'ursteamadmin_single@lundrigan.ca', 'display_name' => 'Team Administrator Single', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'team_admin', 'managedTeams' => [1] ]] ],
        [ 'username' => 'teamadmin_multi', 'email' => 'ursteamadmin_multi@lundrigan.ca', 'display_name' => 'Team Administrator Multi', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'team_admin', 'managedTeams' => [1,2] ]] ],
        [ 'username' => 'competitionadmin', 'email' => 'urscompetitionadmin@lundrigan.ca', 'display_name' => 'Competition Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'competition_admin' ]] ],
        [ 'username' => 'competitionadmin_single', 'email' => 'urscompetitionadmin_single@lundrigan.ca', 'display_name' => 'Competition Administrator Single', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'competition_admin', 'managedCompetitions' => [1] ]] ],
        [ 'username' => 'competitionadmin_multi', 'email' => 'urscompetitionadmin_multi@lundrigan.ca', 'display_name' => 'Competition Administrator Multi', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'competition_admin', 'managedCompetitions' => [1,2] ]] ],
        [ 'username' => 'unionadmin', 'email' => 'ursunionadmin@lundrigan.ca', 'display_name' => 'Union Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'union_admin' ]] ],
        [ 'username' => 'unionadmin_single', 'email' => 'ursunionadmin_single@lundrigan.ca', 'display_name' => 'Union Administrator Single', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'union_admin', 'managedUnions' => [1] ]] ],
        [ 'username' => 'unionadmin_multi', 'email' => 'ursunionadmin_multi@lundrigan.ca', 'display_name' => 'Union Administrator Multi', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'union_admin', 'managedUnions' => [1,2] ]] ],
        [ 'username' => 'memberone', 'email' => 'ursmemberone@lundrigan.ca', 'display_name' => 'Member Single', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'member' ]] ],
        [ 'username' => 'membertwo', 'email' => 'ursmembertwo@lundrigan.ca', 'display_name' => 'Member Two', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'member' ]] ],
    );

}
