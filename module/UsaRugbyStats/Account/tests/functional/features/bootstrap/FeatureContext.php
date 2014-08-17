<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
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

    /**
     * @Then /^I navigate to the Account Profile page$/
     */
    public function iNavigateToTheAccountProfilePage()
    {
        //@TODO use route name instead
        $this->visit('/account');
        $this->assertPageAddress('/account');
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
