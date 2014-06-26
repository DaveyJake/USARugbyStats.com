<?php
namespace UsaRugbyStats\Competition\Fixtures\Development;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UsaRugbyStats\Competition\Entity\Union;

class TestUnionsFixture implements FixtureInterface,  ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function load(ObjectManager $manager)
    {
        echo "\nCreating Demo Unions...\n";

        $svc  = $this->getServiceLocator()->get('usarugbystats_competition_union_service');

        foreach ($this->unionData as $union) {
            echo " - {$union['name']}\n";

            $entity = $svc->create(['union' => $union]);
            if (! $entity instanceof Union) {
                echo "ERROR: Failed to create union: " . $union['name'] . "\n";
                continue;
            }
            $manager->persist($entity);
            unset($entity);
        }

        $manager->flush();
    }

    protected $unionData = array(
        [ 'name' => 'Test Union #1' ],
        [ 'name' => 'Test Union #2' ],
    );

}
