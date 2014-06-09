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
     * @Given /^I navigate to the Add New User page of the Account Administration Panel$/
     */
    public function iNavigateToTheAddNewUserPageOfTheAccountAdministrationPanel()
    {
        $this->visit('/admin/user/create');
        $this->iShouldBeOnTheAddNewUserPageOfTheAccountAdministrationPanel();
    }

    /**
     * @Then /^I should be on the Add New User page of the Account Administration Panel$/
     */
    public function iShouldBeOnTheAddNewUserPageOfTheAccountAdministrationPanel()
    {
        $this->assertPageAddress('/admin/user/create');
    }

    /**
     * @Given /^I should see that "([^"]*)" field has value "([^"]*)"$/
     */
    public function iShouldSeeThatFieldHasValue($arg1, $arg2)
    {
        $this->assertFieldContains($arg1, $arg2);
    }

    /**
     * @Then /^I click the Add Role Assigment dropdown$/
     */
    public function iClickTheAddRoleAssigmentDropdown()
    {
        $session = $this->getSession();
        $dropdown = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', '.useraccount-rbacrole-add')
        );
        $dropdown->click();
    }

    /**
     * @Given /^I click the "([^"]*)" type under the Add Role Assignment dropdown$/
     */
    public function iClickTheTypeUnderTheAddRoleAssignmentDropdown($arg1)
    {
        $session = $this->getSession();
        $selection = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', '#AddRoleAssignmentButtonMenu a[data-key='.$arg1.']')
        );
        $selection->click();

        $session->wait(5000, "$('.rbac-assignment.rbac-assignment-".str_replace('-','',$arg1)."').length == 1   ");
    }

    /**
     * @Given /^I click the add team button on the Team Administrator dialog$/
     */
    public function iClickTheAddTeamButtonOnTheTeamAdministratorDialog()
    {
        $session = $this->getSession();
        $addbutton = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', '.rbac-assignment-teamadmin a.rbac-assignment-addteam')
        );
        $addbutton->click();

        $session->wait(5000, "$('.rbac-assignment-teamadmin select').length == 1");
    }

    /**
     * @Given /^I select team "([^"]*)" from the "([^"]*)" managedTeam field$/
     */
    public function iSelectTeamFromTheManagedteamField($arg1, $arg2)
    {
        $session = $this->getSession();
        $select = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', '.rbac-assignment-teamadmin select[name*="[managedTeams][' . $arg2 . ']"]')
        );
        $select->selectOption($arg1);
    }

    /**
     * @Then /^navigate to the Edit User page of the Account Administration Panel for user with "([^"]*)" "([^"]*)"$/
     */
    public function navigateToTheEditUserPageOfTheAccountAdministrationPanelForUserWith($arg1, $arg2)
    {
        $session = $this->getSession();
        $id = trim($session->evaluateScript("return $('td.cell-{$arg1}:contains(\"{$arg2}\")').closest('tr').find('td.cell-id').html();"));
        if ( empty($id) ) {
            throw new \RuntimeException('Could not locate ID of newly-created user');
        }
        $this->iNavigateToTheEditPageForUser($id);
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

            file_put_contents('/tmp/screenshot.png', $this->getSession()->getDriver()->getScreenshot());

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/upload');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($this->getSession()->getDriver()->getScreenshot())));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . getenv('IMGUR_CLIENT_ID')));
            $result = curl_exec($ch);
            curl_close($ch);

            $imageData = json_decode($result);
            if ( $imageData instanceof \stdClass && $imageData->success ) {
                $this->printDebug('Screenshot uploaded to imgur: ' . $imageData->data->link . ' (deletehash = ' . $imageData->data->deletehash . ')');
            } else {
                $this->printDebug($result);
            }

            $filename = '/tmp/' . uniqid('failure_') . '.html';
            file_put_contents($filename, $this->getSession()->getPage()->getContent());
            $this->printDebug('Page contents dumped to file: ' . $filename);
        }
    }
}
