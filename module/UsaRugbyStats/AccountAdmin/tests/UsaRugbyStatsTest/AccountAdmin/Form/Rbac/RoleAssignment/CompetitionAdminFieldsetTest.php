<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset;

class CompetitionAdminFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager'); 
        $fieldset = new CompetitionAdminFieldset($om);
        
        $this->assertEquals('league-admin', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));
        
        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }

}
