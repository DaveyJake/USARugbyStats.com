<?php
namespace UsaRugbyStats\Account\Fixtures\Doctrine;

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
        return [ 'UsaRugbyStats\Account\Fixtures\Doctrine\RbacRoleFixture'];
    }

    protected $permissions = [
        'competition.union.list' => ['member'],
        'competition.union.create' => ['super_admin'],
        'competition.union.update' => ['union_admin'],
        'competition.union.update.teams' => ['super_admin'],
        'competition.union.delete' => ['super_admin'],

        'competition.team.list' => ['member'],
        'competition.team.create' => ['super_admin'],
        'competition.team.update' => ['team_admin'],
        'competition.team.update.union' => ['super_admin'],
        'competition.team.delete' => ['super_admin'],

        'competition.competition.list' => ['member'],
        'competition.competition.create' => ['super_admin'],
        'competition.competition.update' => ['union_admin', 'competition_admin'],
        'competition.competition.update.details' => ['competition_admin'],
        'competition.competition.update.divisions' => ['union_admin', 'competition_admin'],
        'competition.competition.update.matches' => ['union_admin', 'competition_admin'],
        'competition.competition.delete' => ['super_admin'],

        'competition.competition.division.team.add' => ['competition_admin'],
        'competition.competition.division.team.remove' => ['competition_admin'],

        'competition.competition.match.list' => ['member'],
        'competition.competition.match.create' => ['union_admin', 'competition_admin'],
        'competition.competition.match.update' => ['team_admin', 'competition_admin'],
        'competition.competition.match.delete' => ['union_admin', 'competition_admin'],

        'competition.competition.match.details.change' => ['team_admin', 'competition_admin'],
        'competition.competition.match.team.change' => ['union_admin', 'competition_admin'],
        'competition.competition.match.team.roster.change' => ['team_admin', 'competition_admin'],
        'competition.competition.match.team.events.change' => ['team_admin', 'competition_admin'],
        'competition.competition.match.signatures.change' => ['team_admin', 'competition_admin'],

        'competition.location.list' => ['member'],
        'competition.location.view' => ['member'],
        'competition.location.create' => ['team_admin', 'competition_admin'],
        'competition.location.update' => ['team_admin', 'competition_admin'],
    ];
}
