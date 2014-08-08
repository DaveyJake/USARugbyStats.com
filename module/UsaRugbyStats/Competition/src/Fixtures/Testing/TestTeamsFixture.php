<?php
namespace UsaRugbyStats\Competition\Fixtures\Testing;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UsaRugbyStats\Competition\Entity\Team;

class TestTeamsFixture implements FixtureInterface, DependentFixtureInterface,  ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function load(ObjectManager $manager)
    {
        echo "\nCreating Demo Teams...\n";

        $svc  = $this->getServiceLocator()->get('usarugbystats_competition_team_service');

        foreach ($this->teamData as $team) {
            echo " - {$team['name']}\n";

            $session = $svc->startSession();
            $session->form = clone $svc->getCreateForm();
            $session->entity = new Team();
            $svc->prepare();

            $entity = $svc->create(['team' => $team]);
            if (! $entity instanceof Team) {
                echo "ERROR: Failed to create team: " . $team['name'] . "\n";
                continue;
            }
            unset($entity);
        }
    }

    public function getDependencies()
    {
        return [ 'UsaRugbyStats\Competition\Fixtures\Testing\TestUnionsFixture' ];
    }

    protected $teamData = array(
        [ 'name' => 'Test Team #1', 'union' => 1 ],
        [ 'name' => 'Test Team #2', 'union' => 1 ],
        [ 'name' => 'Test Team #3', 'union' => 1 ],
        [ 'name' => 'Test Team #4', 'union' => 1 ],
        [ 'name' => 'Test Team #5', 'union' => 2 ],
        [ 'name' => 'Test Team #6', 'union' => 2 ],
        [ 'name' => 'Test Team #7', 'union' => 2 ],
        [ 'name' => 'Test Team #8', 'union' => 2 ],
    );

}