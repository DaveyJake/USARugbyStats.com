Feature: Team Administration Panel - Create Team
  In order to create a new team
  As a super_admin user
  I need to be able to interact with the Add New Team section of the Team Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New Team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"
    Then I should not receive an authorization error
    And I should see "Create Team"
    And I should see "Team Details"
      
  @javascript
  Scenario: Administrator can create a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[name] | Behat Team |
    And select "Test Union #2" from "team[union]"
    And I press "Create Team"
    Then I should be on "/admin/team/edit/9"
    And I should see "The team was created successfully!"
    And I should see "Update Team"
    And the "team[name]" field should contain "Behat Team"
      
  @javascript
  Scenario: Name is required when creating a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[name] |  |
    And select "Test Union #1" from "team[union]"
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "Value is required and can't be empty"
    And the "team[name]" field should contain ""
    And the "team[union]" field should contain "1"
      
  @javascript
  Scenario: Union is required when creating a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[name] | Foobar Bazbat |
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "Value is required and can't be empty"
    And the "team[union]" field should contain ""