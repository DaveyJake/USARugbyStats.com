Feature: Competition Administration Panel - Create Competition
  In order to create a new competition
  As a super_admin user
  I need to be able to interact with the Add New Competition section of the Competition Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New Competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"
    Then I should not receive an authorization error
    And I should see "Create Competition"
    And I should see "Competition Details"
      
  @javascript
  Scenario: Administrator can create a new competition with no teams
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Behat Competition |
       | competition[type]      | L |
       | competition[variant]   | 15s |
    And I press "Create Competition"
    Then I should be on "/admin/competition/edit/3" 
    And I should see "The competition was created successfully!"
    And I should see "Behat Competition"
    And the "competition[name]" field should contain "Behat Competition"
            
  @javascript
  Scenario: Administrator cannot create a new competition with same name as an existing competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Behat Competition |
       | competition[type]      | L |
       | competition[variant]   | 15s |
    And I press "Create Competition"
    Then I should be on "/admin/competition/create"
    And I should see "There is already another object matching"

  @javascript
  Scenario: Name is required when creating a new competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"
    When I fill in the following:
       | competition[name] |  |
    And I press "Create Competition"
    Then I should be on "/admin/competition/create"
    And I should see "Value is required and can't be empty"
    And the "competition[name]" field should contain ""
      
  @javascript
  Scenario: Administrator can create a new competition with two empty divisions
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Competition with two empty divisions |
       | competition[type]      | L |
       | competition[variant]   | 15s |
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    And I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
       | competition[divisions][1][name] | Test Division 2 |
    And I press "Create Competition"
    Then I should be on "/admin/competition/edit/4" 
    And I should see "The competition was created successfully!"
    And I should see "Competition with two empty divisions"
    And the "competition[name]" field should contain "Competition with two empty divisions"
    Then I go to "/admin/competition/edit/4/divisions"
    Then I should see 2 ".competition-divisions-division" elements
    And the "competition[divisions][0][name]" field should contain "Test Division 1"
    And the "competition[divisions][1][name]" field should contain "Test Division 2"
            
  @javascript
  Scenario: Administrator cannot create a new competition with two divisions of the same name
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Competition with two divisions of the same name |
       | competition[type]      | L |
       | competition[variant]   | 15s |
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    And I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
       | competition[divisions][1][name] | Test Division 1 |
    And I press "Create Competition"
    Then I should be on "/admin/competition/create"
    And I should see "There is already a division with this name!"

  @javascript
  Scenario: Administrator can create a new competition with two non-empty divisions
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Competition with two non-empty divisions |
       | competition[type]      | L |
       | competition[variant]   | 15s |
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    And I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
       | competition[divisions][1][name] | Test Division 2 |
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #1" from "competition[divisions][0][teamMemberships][0][team]"
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #5" from "competition[divisions][0][teamMemberships][1][team]"
    Then I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #2" from "competition[divisions][1][teamMemberships][0][team]"
    Then I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #8" from "competition[divisions][1][teamMemberships][1][team]"
    Then I press "Create Competition"
    Then I should be on "/admin/competition/edit/5" 
    And I should see "The competition was created successfully!"
    And I should see "Competition with two non-empty divisions"
    And the "competition[name]" field should contain "Competition with two non-empty divisions"
    Then I go to "/admin/competition/edit/5/divisions"
    Then I should see 2 ".competition-divisions-division" elements
    And the "competition[divisions][0][name]" field should contain "Test Division 1"
    And the "competition[divisions][1][name]" field should contain "Test Division 2"
    And I should see 2 ".competition-divisions-division[data-index=0] table tr[data-teamid]" elements
    And I should see 2 ".competition-divisions-division[data-index=1] table tr[data-teamid]" elements

  @javascript
  Scenario: Administrator cannot add the same team to a division more than once
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Competition with team added more than once |
       | competition[variant]   | 15s |
       | competition[type]      | L |
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #1" from "competition[divisions][0][teamMemberships][0][team]"
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element    
    And select "Test Team #1" from "competition[divisions][0][teamMemberships][1][team]"
    Then I press "Create Competition"
    Then I should be on "/admin/competition/create"
    And I should see "This team has already been added!"
    And I should see "This team has already been added!" in the ".competition-divisions-division-teams tr[data-teamindex=1]" element
    
  @javascript
  Scenario: Administrator cannot add the same team to more than one division
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/create"	
    When I fill in the following:
       | competition[name]      | Competition with team added to multiple divisions |
       | competition[variant]   | 15s |
       | competition[type]      | L |
    And I click the ".competition-divisions-add" element
    Then I should see 1 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][0][name] | Test Division 1 |
    Then I click the ".competition-divisions-division[data-index=0] .competition-divisions-division-teams-add" element
    And select "Test Team #1" from "competition[divisions][0][teamMemberships][0][team]"
    Then I click the ".competition-divisions-add" element
    Then I should see 2 ".competition-divisions-division" elements
    Then I fill in the following:
       | competition[divisions][1][name] | Test Division 2 |
    Then I click the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams-add" element
    And select "Test Team #1" from "competition[divisions][1][teamMemberships][0][team]"
    Then I press "Create Competition"
    Then I should be on "/admin/competition/create"
    And I should see "This team has already been added to another division!"
    And I should see "This team has already been added to another division!" in the ".competition-divisions-division[data-index=1] .competition-divisions-division-teams tr[data-teamindex=0]" element