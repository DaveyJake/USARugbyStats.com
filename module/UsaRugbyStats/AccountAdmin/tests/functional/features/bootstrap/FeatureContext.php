<?php

use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{

    /**
     * @Given /^I am authenticated as a super administrator$/
     */
    public function iAmAuthenticatedAsASuperAdministrator()
    {
        //@TODO use route name instead
        $this->visit('/account/login');
        $this->fillField('identity', 'superadmin');
        $this->fillField('credential', 'testtest');
        $this->pressButton('Sign In');

        $this->iShouldNotReceiveAnAuthorizationError();
    }

    /**
     * @Given /^I am authenticated as a member$/
     */
    public function iAmAuthenticatedAsAMember()
    {
        //@TODO use route name instead
        $this->visit('/account/login');
        $this->fillField('identity', 'memberone');
        $this->fillField('credential', 'testtest');
        $this->pressButton('Sign In');

        $this->iShouldNotReceiveAnAuthorizationError();
    }

    /**
     * @Then /^I should receive an authorization error$/
     */
    public function iShouldReceiveAnAuthorizationError()
    {
        //@TODO would be nice to be able to check the status code instead
        $this->assertPageContainsText('You are not allowed to access this resource');
    }

    /**
     * @Given /^I should not receive an authorization error$/
     */
    public function iShouldNotReceiveAnAuthorizationError()
    {
        //@TODO would be nice to be able to check the status code instead
        $this->assertPageNotContainsText('You are not allowed to access this resource');
    }

    /**
     * @Given /^I navigate to the Account Administration Panel$/
     */
    public function iNavigateToTheAccountAdministrationPanel()
    {
        $this->visit('/admin/user/list');
        $this->assertPageAddress('/admin/user/list');
    }

    /**
     * @Given /^I navigate to the edit page for user "([^"]*)"$/
     */
    public function iNavigateToTheEditPageForUser($arg1)
    {
        $this->visit('/admin/user/edit/' . $arg1);
        $this->iAmOnTheEditPageForUser($arg1);
    }

    /**
     * @Given /^I am on the edit page for user "([^"]*)"$/
     */
    public function iAmOnTheEditPageForUser($arg1)
    {
        $this->assertPageAddress('/admin/user/edit/' . $arg1);
    }

    /**
     * @Given /^I should see that "([^"]*)" field has value "([^"]*)"$/
     */
    public function iShouldSeeThatFieldHasValue($arg1, $arg2)
    {
        $this->assertFieldContains($arg1, $arg2);
    }

}
