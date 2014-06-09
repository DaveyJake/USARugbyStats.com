Feature: Account Administration Panel - Edit User Account
  In order to change details relating to user accounts registered in the system
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access panel
    Given I am authenticated as a super administrator
    And I navigate to the Account Administration Panel
    Then I should not receive an authorization error
    And I should see "Users"
    And I should see "Add New User"
  
  @javascript
  Scenario: Unprivileged user (not super_admin) is not allowed to access panel
    Given I am authenticated as a member
    And I navigate to the Account Administration Panel
    Then I should receive an authorization error
      
  @javascript
  Scenario: Administrator can view the profile of an existing user account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    Then I should see "Basic Account Details"
    And I should see "Role-based Access Control"
    And I should see "Save Changes"
      
  @javascript
  Scenario: Administrator can modify the username of an existing user account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | username | changedit |
    And I press "Save Changes"
    And I should see "The user was edited"
    And I navigate to the edit page for user "3"
    And I should see that "username" field has value "changedit"
      
  @javascript
  Scenario: Administrator cannot erase the username of the user
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | username |  |
    And I press "Save Changes"
    And I am on the edit page for user "3"
    And I should see "Value is required and can't be empty"
      
  @javascript
  Scenario: Administrator cannot change the username to one used by another account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | username | memberone |
    And I press "Save Changes"
    And I am on the edit page for user "3"
    And I should see "A record matching the input was found"

  @javascript
  Scenario: Administrator cannot erase the email address of the user
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | email |  |
    And I press "Save Changes"
    And I am on the edit page for user "3"
    And I should see "Value is required and can't be empty"
      
  @javascript
  Scenario: Administrator can modify the email address of an existing user account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | email | test@test.com |
    And I press "Save Changes"
    And I should see "The user was edited"
    And I navigate to the edit page for user "3"
    And I should see that "email" field has value "test@test.com"
    
  @javascript
  Scenario: Administrator cannot provide an invalid email address for the user
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | email | notanemailatall |
    And I press "Save Changes"
    And I am on the edit page for user "3"
    And I should see "The input is not a valid email address"
      
  @javascript
  Scenario: Administrator cannot change the email address to one used by another account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | email | adam+ursmemberone@lundrigan.ca |
    And I press "Save Changes"
    And I am on the edit page for user "3"
    And I should see "A record matching the input was found"
      
  @javascript
  Scenario: Administrator can modify the display name of an existing user account
    Given I am authenticated as a super administrator
    And I navigate to the edit page for user "3"
    When I fill in the following:
       | display_name | Testy McTesterson |
    And I press "Save Changes"
    And I should see "The user was edited"
    And I navigate to the edit page for user "3"
    And I should see that "display_name" field has value "Testy McTesterson"