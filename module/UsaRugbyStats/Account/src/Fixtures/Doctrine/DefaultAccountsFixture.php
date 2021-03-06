<?php
namespace UsaRugbyStats\Account\Fixtures\Doctrine;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Account\Entity\Account;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Math\Rand;

class DefaultAccountsFixture implements FixtureInterface, DependentFixtureInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function load(ObjectManager $manager)
    {
        echo "\nCreating Default Accounts...\n";

        $svc  = $this->getServiceLocator()->get('UsaRugbyStats\AccountAdmin\Service\UserService');

        foreach ($this->accountData as $acct) {
            echo " - {$acct['username']}";

            if ( !isset($acct['password']) || empty($acct['password']) ) {
                $acct['password'] = Rand::getString(24,null,true);
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
        return [ 'UsaRugbyStats\Account\Fixtures\Doctrine\RbacRoleFixture'];
    }

    protected $accountData = array(
        [ 'id' => 1, 'username' => 'consoleadmin', 'email' => 'usarugbymedia+consoleadmin@gmail.com', 'display_name' => 'Console Administrator', 'roleAssignments' => [[ 'type' => 'super_admin' ]] ],
        [ 'id' => 2, 'username' => 'administrator', 'email' => 'usarugbymedia@gmail.com', 'display_name' => 'Administrator', 'roleAssignments' => [[ 'type' => 'super_admin' ]] ],
    );

}
