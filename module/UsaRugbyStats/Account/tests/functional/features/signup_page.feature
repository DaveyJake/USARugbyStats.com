Feature: Account Registraiton Page
  In order to access the application
  As an anonymous user
  I need to be able to create an account if I don't already have one
  
  @javascript
  Scenario: Able to access the registration page.
    Given I am on the registration page
    Then I should see "Account Registration"
    And I should see "Username"
    And I should see "Password"
    And I should see "Password Verify"
      
  @javascript
  Scenario: Able to create an account using the registration page.
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount@lundrigan.ca |
       | username | testaccount |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I navigate to the Account Profile page
    And I should not receive an authorization error
      
  @javascript
  Scenario: Registration process rejects request with existing email address
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount@lundrigan.ca |
       | username | testaccount2 |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "A record matching the input was found"
      
  @javascript
  Scenario: Registration process rejects request with existing username
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount2@lundrigan.ca |
       | username | testaccount |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "A record matching the input was found"
    
  @javascript
  Scenario: Registration process rejects request when display name is empty
    Given I am on the registration page
    When I fill in the following:
       | display_name | |
       | email | urstestaccount@lundrigan.ca |
       | username | testaccount |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "Value is required and can't be empty"
              
  @javascript
  Scenario: Registration process rejects request when email address is empty
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | |
       | username | testaccount |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "Value is required and can't be empty"
              
  @javascript
  Scenario: Registration process rejects request when username is empty
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount@lundrigan.ca |
       | username |  |
       | password | TestAccount!! |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "Value is required and can't be empty"
              
  @javascript
  Scenario: Registration process rejects request when password is empty
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount@lundrigan.ca |
       | username | testaccount |
       | password |  |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "Value is required and can't be empty"
              
  @javascript
  Scenario: Registration process rejects request when passwords don't match
    Given I am on the registration page
    When I fill in the following:
       | display_name | Test Account |
       | email | urstestaccount@lundrigan.ca |
       | username | testaccount |
       | password | TestAccount## |
       | passwordVerify | TestAccount!! |
    And I press "Sign Up!"
    Then I should see "The two given tokens do not match"