<?php
namespace UsaRugbyStatsTest\Competition\Service\Competition;

use Mockery;
use Zend\ServiceManager\ServiceManager;
use UsaRugbyStats\Competition\Service\Competition\MatchServiceFactory;

class MatchServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $sl = new ServiceManager();
        $sl->setAllowOverride(true);

        $mockObjectRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager    = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockObjectRepository);
        $sl->setService('zfcuser_doctrine_em', $mockObjectManager);

        $mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');

        $mockItem = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockItem->shouldReceive('getName')->andReturn('test');
        $mockItem->shouldReceive('getObject')->andReturn(new \stdClass());

        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('get->getTargetElement')->andReturn([$mockItem]);

        $resultingEventTypes = [
            'test' => [
                'name' => 'test',
                'fieldset_class' => get_class($mockItem),
                'entity_class' => 'stdClass'
            ]
        ];

        $sl->setService('usarugbystats_competition_competition_match_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition_competition_match_updateform', $mockUpdateForm);
        $sl->setService('usarugbystats_competition_competition_match_team_fieldset', $mockFieldset);

        $factory = new MatchServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\Competition\MatchService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
        $this->assertEquals($resultingEventTypes, $obj->getAvailableMatchTeamEventTypes());
    }
}
