<?php
namespace UsaRugbyStatsTest\Competition\Form\Competition;

use Mockery;
use UsaRugbyStats\Competition\Form\Competition\MatchUpdateFormFactory;
use UsaRugbyStats\Competition\Form\Competition\MatchCreateFormFactory;

class MatchUpdateFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('setUseAsBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('useAsBaseFieldset')->andReturn(true);
        $mockFieldset->shouldReceive('isBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('getName')->andReturn('match');
        $mockFieldset->shouldReceive('getElements')->andReturn(array());
        $mockFieldset->shouldReceive('getFieldsets')->andReturn(array());
        $mockFieldset->shouldReceive('getObject')->andReturn(new \stdClass());

        $mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('usarugbystats_competition_competition_match_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_inputfilter', $mockInputFilter);

        $factory = new MatchCreateFormFactory();
        $this->serviceManager->setService(
            'usarugbystats_competition_competition_match_createform',
            $factory->createService($this->serviceManager)
        );
    }

    public function testUpdateService()
    {
        $factory = new MatchUpdateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('update-competition-match', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_competition_match_inputfilter'), $obj->getInputFilter()->get('match'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_competition_match_fieldset'), $obj->getFieldsets()['match']);
        $this->assertTrue($obj->has('submit'));
    }

}
