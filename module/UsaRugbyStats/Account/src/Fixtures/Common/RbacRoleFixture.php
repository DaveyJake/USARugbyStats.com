<?php
namespace UsaRugbyStats\Account\Fixtures\Common;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class RbacRoleFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        echo "\nCreating default RBAC role hierarchy...\n";

        $guest = new Role('guest');
        $manager->persist($guest);

        $member = new Role('member');
        $member->addChild($guest);
        $manager->persist($member);

        $teamadmin = new Role('team_admin');
        $teamadmin->addChild($member);
        $manager->persist($teamadmin);

        $compadmin = new Role('competition_admin');
        $compadmin->addChild($member);
        $manager->persist($compadmin);

        $unionadmin = new Role('union_admin');
        $unionadmin->addChild($teamadmin);
        $manager->persist($unionadmin);

        $superadmin = new Role('super_admin');
        $superadmin->addChild($unionadmin);
        $superadmin->addChild($compadmin);
        $manager->persist($superadmin);

        $manager->flush();
    }
}
