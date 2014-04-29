<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\UnionFieldsetFactory;

class UnionFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));

        $this->serviceManager->setService(
            'usarugbystats_competition_union_team_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Union\TeamFieldset')
        );
    }

    public function testCreateService()
    {
        $factory = new UnionFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\UnionFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Union', $object->getObject());
    }
}
