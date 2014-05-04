<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\CardEventFieldsetFactory;

class CardEventFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
    }

    public function testCreateService()
    {
        $factory = new CardEventFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\CardEventFieldset', $object);
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent', $object->getObject());
    }
}
