<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldsetFactory;

class DivisionFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));

        $this->serviceManager->setService(
            'usarugbystats_competition_competition_teammembership_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\TeamMembershipFieldset')
        );
    }

    public function testCreateService()
    {
        $factory = new DivisionFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Division', $object->getObject());
    }
}
