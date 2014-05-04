<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

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
        $this->visit('/account/logout');
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
        $this->visit('/account/logout');
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
     * @Given /^I click the "([^"]*)" element$/
     */
    public function iClickTheElement($arg1)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $arg1)
        );
        if ( is_null($element) ) {
            throw new ElementNotFoundException($session, NULL, $arg1);
        }
        $element->click();
    }

    /**
     * @Given /^I wait up to "([^"]*)" ms for "([^"]*)"$/
     */
    public function iWaitUpToMsFor($arg1, $arg2)
    {
        $session = $this->getSession();
        $session->wait($arg1, $arg2);
    }
}
