<?php
namespace UsaRugbyStats\Account\Fixtures\Common;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Account\Entity\Rbac\Permission;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class PermissionsFixture implements FixtureInterface, DependentFixtureInterface
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
        return [ 'UsaRugbyStats\Account\Fixtures\Common\RbacRoleFixture'];
    }

    protected $permissions = [
        'competition.team.list' => ['member'],
        'competition.team.create' => ['super_admin'],
        'competition.team.update' => ['team_admin'],
        'competition.team.update.union' => ['super_admin'],
        'competition.team.delete' => ['super_admin'],

        'competition.competition.list' => ['member'],
        'competition.competition.create' => ['super_admin'],
        'competition.competition.update' => ['competition_admin'],
        'competition.competition.delete' => ['super_admin'],

        'competition.competition.division.list' => ['member'],
        'competition.competition.division.create' => ['competition_admin'],
        'competition.competition.division.update' => ['competition_admin'],
        'competition.competition.division.delete' => ['competition_admin'],

        'competition.competition.division.team.add' => ['competition_admin'],
        'competition.competition.division.team.remove' => ['competition_admin'],

        'competition.competition.match.list' => ['member'],
        'competition.competition.match.create' => ['competition_admin'],
        'competition.competition.match.update' => ['competition_admin'],
        'competition.competition.match.delete' => ['competition_admin'],

        'competition.competition.match.details.change' => ['competition_admin'],
        'competition.competition.match.team.change' => ['competition_admin'],
        'competition.competition.match.team.roster.change' => ['competition_admin'],
        'competition.competition.match.team.events.change' => ['competition_admin'],
        'competition.competition.match.team.signatures.change' => ['competition_admin'],
    ];
}
