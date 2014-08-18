Feature: Competition Administration Panel - Competition Divisions
  In order to create a new competition
  As a super_admin user
  I need to be able to interact with the Add New Competition section of the Competition Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Manage Divisions
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1/divisions"
    Then I should not receive an authorization error
    And I should see "Test Competition #1"
    And I should see "Competition Divisions"

  @javascript
  Scenario: Administrator cannot create two divisions of the same name
    Given I am authenticated as a super administrator
    When I go to "/admin/competition/edit/1/divisions" 
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    And I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
       | competition[divisions][1][name] | Test Division 1 |
    When I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions"
    And I should see "There is already a division with this name!"
    
  @javascript
  Scenario: Administrator can add divisions
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1/divisions" 
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    When I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    When I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
       | competition[divisions][1][name] | Test Division 2 |
    And I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #1" from "competition[divisions][0][teamMemberships][0][team]"
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #5" from "competition[divisions][0][teamMemberships][1][team]"
    Then I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #2" from "competition[divisions][1][teamMemberships][0][team]"
    Then I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #8" from "competition[divisions][1][teamMemberships][1][team]"
    Given I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions" 
    And I should see "The division assignments were updated successfully!"
    And I should see 2 ".competition-divisions-division" elements
    And the "competition[divisions][0][name]" field should contain "Test Division 1"
    And the "competition[divisions][1][name]" field should contain "Test Division 2"
    And I should see 2 ".competition-divisions-division[data-index=0] table tr[data-teamid]" elements
    And I should see 2 ".competition-divisions-division[data-index=1] table tr[data-teamid]" elements

  @javascript
  Scenario: Cannot add same team to a division more than once
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1/divisions" 
    And I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #8" from "competition[divisions][1][teamMemberships][2][team]"
    Given I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions"
    And I should see "This team has already been added!" 

  @javascript
  Scenario: Cannot add same team to more than one division
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1/divisions" 
    And I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #8" from "competition[divisions][0][teamMemberships][2][team]"
    Given I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions"
    And I should see "This team has already been added!" 
    
  @javascript
  Scenario: Administrator can remove divisions
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1/divisions"
    Then I should see 2 ".competition-divisions-division" elements
    And I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-remove" element
    Then I should see 1 ".competition-divisions-division" elements
    Given I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions"
    Then I should see 1 ".competition-divisions-division" elements
    And the "competition[divisions][0][name]" field should contain "Test Division 2"

  @javascript
  Scenario: Administrator can remove all divisions
    Given I am authenticated as a super administrator
    When I go to "/admin/competition/edit/1/divisions"
    Then I should see 1 ".competition-divisions-division" elements
    And I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-remove" element
    Then I should see 0 ".competition-divisions-division" elements
    When I press "Save Changes"
    Then I should be on "/admin/competition/edit/1/divisions"
    Then I should see 0 ".competition-divisions-division" elements