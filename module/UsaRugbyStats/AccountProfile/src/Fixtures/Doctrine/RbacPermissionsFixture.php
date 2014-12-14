<?php
namespace UsaRugbyStats\AccountProfile\Fixtures\Doctrine;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Account\Entity\Rbac\Permission;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class RbacPermissionsFixture implements FixtureInterface, DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        echo "\nImporting Competition module RBAC permissions...\n";

        $repoPermission = $manager->getRepository('UsaRugbyStats\Account\Entity\Rbac\Permission');
        $repoRole = $manager->getRepository('UsaRugbyStats\Account\Entity\Rbac\Role');

        foreach ($this->permissions as $perm => $roles) {
            echo "Creating permission '" . $perm . "'\n";

            $objPermission = $repoPermission->findOneByName($perm) ?: new Permission();
            $objPermission->setName($perm);
            $manager->persist($objPermission);

            foreach ($roles as $role) {
                echo " - Adding to role '" . $role . "'...";
                $objRole = $repoRole->findOneByName($role);
                if (! $objRole instanceof Role) {
                    echo "NOT FOUND!\n";
                    continue;
                }

                $objRole->addPermission($objPermission);
                $manager->persist($objRole);
                echo "OK\n";
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ 'UsaRugbyStats\Account\Fixtures\Doctrine\RbacRoleFixture' ];
    }

    protected $permissions = [
        'account.profile' => ['competition_admin', 'team_admin'],

        'account.profile.zfcuser.username' => ['super_admin'],
        'account.profile.zfcuser.email' => ['member'],
        'account.profile.zfcuser.display_name' => ['super_admin'],
        'account.profile.zfcuser.password' => ['member'],
        'account.profile.zfcuser.passwordVerify' => ['member'],

        'account.profile.extprofile.firstName' => ['member'],
        'account.profile.extprofile.lastName' => ['member'],
        'account.profile.extprofile.telephoneNumber' => ['member'],
        'account.profile.extprofile.photoSource' => ['member'],
        'account.profile.extprofile.custom_photo' => ['member'],

        'account.profile.personalstats.height_ft' => ['member'],
        'account.profile.personalstats.height_in' => ['member'],
        'account.profile.personalstats.weight_lbs' => ['member'],
        'account.profile.personalstats.weight_oz' => ['member'],
        'account.profile.personalstats.benchPress' => ['member'],
        'account.profile.personalstats.sprintTime' => ['member'],
    ];
}
