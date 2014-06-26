<?php
namespace UsaRugbyStats\Competition\Fixtures\Development;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UsaRugbyStats\Competition\Entity\Competition;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TestCompetitionsFixture implements FixtureInterface, DependentFixtureInterface,  ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function load(ObjectManager $manager)
    {
        echo "\nCreating Demo Competitions...\n";

        $svc  = $this->getServiceLocator()->get('usarugbystats_competition_competition_service');

        foreach ($this->competitionData as $competition) {
            echo " - {$competition['name']}\n";

            $entity = $svc->create(['competition' => $competition]);
            if (! $entity instanceof Competition) {
                echo "ERROR: Failed to create competition: " . $competition['name'] . "\n";
                continue;
            }
            $manager->persist($entity);
            unset($entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ 'UsaRugbyStats\Competition\Fixtures\Development\TestTeamsFixture' ];
    }

    protected $competitionData = array(
        [ 'name' => 'Test Competition #1', 'variant' => '15s', 'startDate' => '2014-07-17T14:22-06:00', 'endDate' => '2014-07-23T08:09-06:00' ],
        [ 'name' => 'Test Competition #2', 'variant' => '7s', 'startDate' => '2014-07-08T14:22-06:00', 'endDate' => '2014-07-23T08:09-06:00' ],
    );

}
