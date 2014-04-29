<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\CompetitionFieldsetFactory;

class CompetitionFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));

        $this->serviceManager->setService(
            'usarugbystats_competition_competition_division_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldset')
        );
    }

    public function testCreateService()
    {
        $factory = new CompetitionFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\CompetitionFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition', $object->getObject());
    }
}
