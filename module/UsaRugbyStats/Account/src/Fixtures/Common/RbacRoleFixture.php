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
        $guest = new Role('guest');        
        $manager->persist($guest);
        
        $member = new Role('member');
        $member->addChild($guest);
        $manager->persist($member);
        
        $teamadmin = new Role('team_admin');
        $teamadmin->addChild($member);
        $manager->persist($teamadmin);

        $leagueadmin = new Role('league_admin');
        $leagueadmin->addChild($teamadmin);
        $manager->persist($leagueadmin);

        $unionadmin = new Role('union_admin');
        $unionadmin->addChild($leagueadmin);
        $manager->persist($unionadmin);

        $superadmin = new Role('super_admin');
        $superadmin->addChild($unionadmin);
        $manager->persist($superadmin);

        $manager->flush();
    }
}
