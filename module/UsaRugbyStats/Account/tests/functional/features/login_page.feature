Feature: Login Page
  In order to access the application
  As an anonymous user
  I need to be able to interact with the authentication service
  
  @javascript
  Scenario: Able to access the login page.
    Given I am on the login page
    Then I should see "Username"
    And I should see "Password"
    And I should see "Sign In"

  @javascript
  Scenario: Able to sign in with valid credentials
    Given I am on the login page
    When I fill in the following:
       | identity | superadmin |
       | credential | testtest |
    And I press "Sign In"
    Then I navigate to the Account Profile page
    And I should not receive an authorization error
  
  @javascript
  Scenario: Unable to sign in with invalid credentials
    Given I am on the login page
    When I fill in the following:
       | identity | foobar |
       | credential | dpfoasjfjfdd |
    And I press "Sign In"
    Then I should be on the login page
    And I should see "Authentication failed. Please try again."
