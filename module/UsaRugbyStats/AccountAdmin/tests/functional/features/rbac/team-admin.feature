Feature: Account Administration Panel - Edit User Account - TeamAdmin RBAC
  In order to change details relating to user accounts' TeamAdmin roles
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel
        
  @javascript
  Scenario: Add a new TeamAdmin role to an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/3" 
	And I click the ".useraccount-rbacrole-add" element
	And I click the "#AddRoleAssignmentButtonMenu a[data-key=team_admin]" element
	And I wait up to "5000" ms for "$('#RoleAssignmentContainer .rbac-assignment-teamadmin').length == 1"
	Then I should see an "#RoleAssignmentContainer .rbac-assignment-teamadmin" element
	And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/3"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements

  @javascript
  Scenario: Add teams to an existing TeamAdmin role of an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/3"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin" elements
	And I click the ".rbac-assignment-addteam" element
	And I click the ".rbac-assignment-addteam" element 
	Then I should see 2 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements
	Given I select "Test Team #2" from "roleAssignments[0][managedTeams][1]"
	Given I select "Test Team #1" from "roleAssignments[0][managedTeams][2]"
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/3"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin" elements
    And I should see 2 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on a team in TeamAdmin will drop the team
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/3" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=2] a.dropteam" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=2]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/3"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on all teams in TeamAdmin will empty the collection
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/3" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=1] a.dropteam" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid=1]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/3"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin table > tbody > tr[data-teamid]" elements
  
  @javascript
  Scenario: Clicking the trash can icon on a TeamAdmin role will drop the role
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/3" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-teamadmin a.rbac-assignment-delete" element
	Then I should not see an "#RoleAssignmentContainer .rbac-assignment-teamadmin" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/3"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-teamadmin" elements
