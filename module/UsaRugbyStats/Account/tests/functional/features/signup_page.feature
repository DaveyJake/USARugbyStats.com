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
