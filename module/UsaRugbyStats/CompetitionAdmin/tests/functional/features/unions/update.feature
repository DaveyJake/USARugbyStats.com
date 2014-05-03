Feature: Union Administration Panel - Update Union
  In order to update an existing new union
  As a super_admin user
  I need to be able to interact with the Edit Union section of the Union Administration panel
  
  Background:
    Given I am authenticated as a super administrator    

  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Edit Union
    Given I go to "/admin/union/edit/1"
    Then I should not receive an authorization error
    And I should see "Update Union"
    And the "union[name]" field should contain "Test Union #1"
    # Union #1 from fixture has 4 teams [1-4]
    And I should see an ".union-teams table tr[data-teamid=1]" element
    And I should see "Test Team #1" in the ".union-teams table tr[data-teamid=1] .column-name" element
    And I should see an ".union-teams table tr[data-teamid=2]" element
    And I should see "Test Team #2" in the ".union-teams table tr[data-teamid=2] .column-name" element
    And I should see an ".union-teams table tr[data-teamid=3]" element
    And I should see "Test Team #3" in the ".union-teams table tr[data-teamid=3] .column-name" element
    And I should see an ".union-teams table tr[data-teamid=4]" element
    And I should see "Test Team #4" in the ".union-teams table tr[data-teamid=4] .column-name" element
    
  @javascript
  Scenario: Clicking the trash can icon in the team row will drop the team
    Given I go to "/admin/union/edit/1"
	And I click the ".union-teams table tr[data-teamid=1] a.dropteam" element
	Then I should not see an ".union-teams table tr[data-teamid=1]" element
    And I press "submit"
    Then I should be on "/admin/union/edit/1" 
    And I should see "The union was updated successfully!"
    And I should see "Update Union"
    And I should see an ".union-teams table tr[data-teamid=2]" element
    And I should see an ".union-teams table tr[data-teamid=3]" element
    And I should see an ".union-teams table tr[data-teamid=4]" element
    But I should not see an ".union-teams table tr[data-teamid=1]" element
    
  @javascript
  Scenario: Can re-add Team #1 after it was removed
    Given I go to "/admin/union/edit/1"
    And I should see 3 ".union-teams table tr[data-teamid]" elements
    And I should not see an ".union-teams table tr[data-teamid=1]" element
	When I click the ".union-teams a.union-teams-add" element
	Then I should see 4 ".union-teams table tr[data-teamid]" elements
	And select "Test Team #1" from "union[teams][3][id]"
    And I press "submit"
    Then I should be on "/admin/union/edit/1" 
    And I should see "The union was updated successfully!"
    And I should see "Update Union"
    And I should see an ".union-teams table tr[data-teamid=2]" element
    And I should see an ".union-teams table tr[data-teamid=3]" element
    And I should see an ".union-teams table tr[data-teamid=4]" element
    And I should see an ".union-teams table tr[data-teamid=1]" element
    
  @javascript
  Scenario: Clicking the trash can icon for all teams will remove all teams
    Given I go to "/admin/union/edit/1"
	And I click the ".union-teams table tr[data-teamid=1] a.dropteam" element
	Then I should not see an ".union-teams table tr[data-teamid=1]" element
	And I click the ".union-teams table tr[data-teamid=2] a.dropteam" element
	Then I should not see an ".union-teams table tr[data-teamid=2]" element
	And I click the ".union-teams table tr[data-teamid=3] a.dropteam" element
	Then I should not see an ".union-teams table tr[data-teamid=3]" element
	And I click the ".union-teams table tr[data-teamid=4] a.dropteam" element
	Then I should not see an ".union-teams table tr[data-teamid=4]" element
    And I press "submit"
    Then I should be on "/admin/union/edit/1" 
    And I should see "The union was updated successfully!"
    And I should see "Update Union"
    And I should not see an ".union-teams table tr[data-teamid=1]" element
    And I should not see an ".union-teams table tr[data-teamid=2]" element
    And I should not see an ".union-teams table tr[data-teamid=3]" element
    And I should not see an ".union-teams table tr[data-teamid=4]" element