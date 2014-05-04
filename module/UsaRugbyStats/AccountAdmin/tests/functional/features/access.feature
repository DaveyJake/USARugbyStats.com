Feature: Account Administration Panel
  In order to manage the user accounts registered in the system
  As a super_admin user
  I need to be able to interact with the Account Administration Panel
  
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
