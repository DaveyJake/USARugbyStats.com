<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }
    
    /**
     * @Given /^I am on the login page$/
     */
    public function iAmOnTheLoginPage()
    {
        //@TODO use route name instead
        $this->visit('/account/login');
    }

    /**
     * @Then /^I should be on the login page$/
     */
    public function iShouldBeOnTheLoginPage()
    {
        //@TODO use route name instead
        $this->assertPageAddress('/account/login');
    }
    
    /**
     * @Given /^I am on the registration page$/
     */
    public function iAmOnTheRegistrationPage()
    {
        //@TODO use route name instead
        $this->visit('/account/register');
    }

    /**
     * @Then /^I should be on the registration page$/
     */
    public function iShouldBeOnTheRegistrationPage()
    {
        //@TODO use route name instead
        $this->assertPageAddress('/account/register');
    }
}
