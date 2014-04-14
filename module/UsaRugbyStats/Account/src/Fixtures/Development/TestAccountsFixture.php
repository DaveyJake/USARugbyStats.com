<?php
namespace UsaRugbyStats\Account\Fixtures\Development;

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
        echo "\nCreating Demo Accounts...\n";

        $svc  = $this->getServiceLocator()->get('UsaRugbyStats\AccountAdmin\Service\UserService');

        foreach ($this->accountData as $acct) {
            echo " - {$acct['username']}";

            if ( !isset($acct['password']) || empty($acct['password']) ) {
                $acct['password'] = uniqid();
                echo " (password = " . $acct['password'] . " )";
            }
            $acct['passwordVerify'] = $acct['password'];

            echo "\n";

            $form = $this->getServiceLocator()->get('zfcuseradmin_createuser_form');
            $entity = $svc->create($form, $acct);
            if (! $entity instanceof Account) {
                echo "ERROR: Failed to create account: " . $acct['username'] . "\n";
                continue;
            }
            $manager->persist($entity);
            unset($form, $entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ 'UsaRugbyStats\Account\Fixtures\Common\RbacRoleFixture'];
    }

    protected $accountData = array(
        [ 'username' => 'superadmin', 'email' => 'adam+urssuperadmin@lundrigan.ca', 'display_name' => 'Super Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'super-admin' ]] ],
        [ 'username' => 'teamadmin', 'email' => 'adam+ursteamadmin@lundrigan.ca', 'display_name' => 'Team Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'team-admin' ]] ],
        [ 'username' => 'competitionadmin', 'email' => 'adam+urscompetitionadmin@lundrigan.ca', 'display_name' => 'Competition Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'competition-admin' ]] ],
        [ 'username' => 'unionadmin', 'email' => 'adam+ursunionadmin@lundrigan.ca', 'display_name' => 'Union Administrator', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'union-admin' ]] ],
        [ 'username' => 'memberone', 'email' => 'adam+ursmemberone@lundrigan.ca', 'display_name' => 'Member One', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'member' ]] ],
        [ 'username' => 'membertwo', 'email' => 'adam+ursmembertwo@lundrigan.ca', 'display_name' => 'Member Two', 'password' => 'testtest', 'roleAssignments' => [[ 'type' => 'member' ]] ],
    );

}
