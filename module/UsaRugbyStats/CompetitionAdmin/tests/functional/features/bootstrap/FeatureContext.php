<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Driver\Selenium2Driver;

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
            throw new ElementNotFoundException($session, null, $arg1);
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

    /**
     * Take screenshot when step fails.
     *
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if (4 === $event->getResult()) {
            $driver = $this->getSession()->getDriver();
            if (!($driver instanceof Selenium2Driver)) {
                //throw new UnsupportedDriverActionException('Taking screenshots is not supported by %s, use Selenium2Driver instead.', $driver);
                return;
            }

            $failureid = uniqid('failure_');

            file_put_contents("/tmp/{$failureid}.png", $this->getSession()->getDriver()->getScreenshot());

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/upload');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($this->getSession()->getDriver()->getScreenshot())));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . getenv('IMGUR_CLIENT_ID')));
            $result = curl_exec($ch);
            curl_close($ch);

            $imageData = json_decode($result);
            if ($imageData instanceof \stdClass && $imageData->success) {
                $this->printDebug('Screenshot uploaded to imgur: ' . $imageData->data->link . ' (deletehash = ' . $imageData->data->deletehash . ')');
            } else {
                $this->printDebug($result);
            }

            $filename = "/tmp/{$failureid}.html";
            file_put_contents($filename, $this->getSession()->getPage()->getContent());
            $this->printDebug('Page contents dumped to file: ' . $filename);
        }
    }
}
