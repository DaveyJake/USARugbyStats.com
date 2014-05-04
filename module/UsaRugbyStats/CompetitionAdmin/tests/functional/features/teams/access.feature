Feature: Team Administration Panel
  In order to manage the teams registered in the system
  As a super_admin user
  I need to be able to interact with the Team Administration Panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access panel
    Given I am authenticated as a super administrator
    And I go to "/admin/team/list"
    Then I should not receive an authorization error
    And I should see "Teams"
    And I should see "Add New Team"
  
  @javascript
  Scenario: Unprivileged user (not super_admin) is not allowed to access panel
    Given I am authenticated as a member
    And I go to "/admin/team/list"
    Then I should receive an authorization error
