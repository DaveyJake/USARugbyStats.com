<?php
namespace UsaRugbyStatsTest\AccountAdmin\Service;

use Mockery;
use UsaRugbyStats\AccountAdmin\Service\UserService;

class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $userMapper;

    public function setUp()
    {
        $this->service = new UserService();

        $this->userMapper = Mockery::mock('ZfcUser\Mapper\UserInterface');
        $this->service->setUserMapper($this->userMapper);

        $this->service->setAvailableRoleAssignments(array(
            'member' => array(
                'name' => 'member',
                'fieldset_class' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset',
                'entity_class' => 'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member',
            ),
            'super_admin' => array(
                'name' => 'super_admin',
                'fieldset_class' => 'UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset',
                'entity_class' => 'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin',
            ),
        ));

        $mockZfcUserOptions = new \ZfcUser\Options\ModuleOptions();
        $this->service->setZfcUserOptions($mockZfcUserOptions);

        $mockOptions = new \ZfcUserAdmin\Options\ModuleOptions();
        $this->service->setOptions($mockOptions);
    }

    public function testCreateDoesPopulateTheRoleAssignmentsInTheFormDataBeforeFormHydratesIt()
    {
        $test = $this;
        $formData = array(
            'roleAssignments' => array(
                array('type' => 'member'),
                array('type' => 'super_admin'),
            ),
        );

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('bind')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertTrue(isset($data['roleAssignments'][0]));
            $test->assertArrayHasKey('type', $data['roleAssignments'][0]);
            $test->assertTrue(isset($data['roleAssignments'][1]));
            $test->assertArrayHasKey('type', $data['roleAssignments'][1]);

            return true;
        }));
        $form->shouldReceive('getData')->andReturn($entity);
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('insert')->andReturnNull();

        $this->service->create($form, $formData);
    }

    public function testCreateDoesPopulateTheRoleAssignmentsInTheFormDataBeforeFormHydratesItWhenTypeFieldIsInCamelCaseFormat()
    {
        $test = $this;
        $formData = array(
            'roleAssignments' => array(
                array('type' => 'SuperAdmin'),
            ),
        );

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('bind')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertTrue(isset($data['roleAssignments'][0]));
            $test->assertArrayHasKey('type', $data['roleAssignments'][0]);

            return true;
        }));
        $form->shouldReceive('getData')->andReturn($entity);
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('insert')->andReturnNull();

        $this->service->create($form, $formData);
    }

    public function testCreateShouldRejectUndefinedRoleAssignmentTypes()
    {
        $test = $this;
        $formData = array(
            'roleAssignments' => array(
                array('type' => 'FooBarBazBat'),
            ),
        );

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('bind')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertEmpty($data['roleAssignments']);

            return true;
        }));
        $form->shouldReceive('getData')->andReturn($entity);
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('insert')->andReturnNull();

        $this->service->create($form, $formData);
    }

    public function testCreateShouldAddEmptyRoleAssignmentsKeyIfOneIsNotInTheFormData()
    {
        $test = $this;
        $formData = array();

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('bind')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertEmpty($data['roleAssignments']);

            return true;
        }));
        $form->shouldReceive('getData')->andReturn($entity);
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('insert')->andReturnNull();

        $this->service->create($form, $formData);
    }

    public function testEditDoesPopulateTheRoleAssignmentsInTheFormDataBeforeFormHydratesIt()
    {
        $test = $this;
        $formData = array(
            'roleAssignments' => array(
                array('type' => 'member'),
                array('type' => 'super_admin'),
            ),
        );

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('setUser')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertTrue(isset($data['roleAssignments'][0]));
            $test->assertArrayHasKey('type', $data['roleAssignments'][0]);
            $test->assertTrue(isset($data['roleAssignments'][1]));
            $test->assertArrayHasKey('type', $data['roleAssignments'][1]);

            return true;
        }));
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('update')->andReturnNull();

        $this->service->edit($form, $formData, $entity);
    }

    public function testEditShouldRejectUndefinedRoleAssignmentTypes()
    {
        $test = $this;
        $formData = array(
            'roleAssignments' => array(
                array('type' => 'FooBarBazBat'),
            ),
        );

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('setUser')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertEmpty($data['roleAssignments']);

            return true;
        }));
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('update')->andReturnNull();

        $this->service->edit($form, $formData, $entity);
    }

    public function testEditShouldAddEmptyRoleAssignmentsKeyIfOneIsNotInTheFormData()
    {
        $test = $this;
        $formData = array();

        $entity = new \UsaRugbyStats\Account\Entity\Account();

        $form = Mockery::mock('Zend\Form\Form');
        $form->shouldReceive('setUser')->andReturnNull();
        $form->shouldReceive('setData')->with(Mockery::on(function ($data) use ($test) {
            $test->assertArrayHasKey('roleAssignments', $data);
            $test->assertEmpty($data['roleAssignments']);

            return true;
        }));
        $form->shouldReceive('isValid')->andReturn(true);

        $this->userMapper->shouldReceive('update')->andReturnNull();

        $this->service->edit($form, $formData, $entity);
    }
}
