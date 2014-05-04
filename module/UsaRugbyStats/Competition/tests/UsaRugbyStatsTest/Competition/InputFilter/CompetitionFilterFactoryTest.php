<?php
namespace UsaRugbyStatsTest\Competition\InputFilter;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\CompetitionFilterFactory;

class CompetitionFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $mockFilter = Mockery::mock('Zend\InputFilter\BaseInputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
        $this->serviceManager->setService('usarugbystats_competition_competition_division_inputfilter', $mockFilter);
    }

    public function testCreateService()
    {
        $factory = new CompetitionFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\CompetitionFilter', $object);
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $object->get('divisions'));
    }
}
