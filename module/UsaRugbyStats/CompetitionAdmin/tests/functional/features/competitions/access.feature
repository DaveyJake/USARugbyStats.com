Feature: Competition Administration Panel
  In order to manage the competitions registered in the system
  As a super_admin user
  I need to be able to interact with the Competition Administration Panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access panel
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/list"
    Then I should not receive an authorization error
    And I should see "Competitions"
    And I should see "Add New Competition"
  
  @javascript
  Scenario: Unprivileged user (not super_admin) is not allowed to access panel
    Given I am authenticated as a member
    And I go to "/admin/competition/list"
    Then I should receive an authorization error
