Feature: Account Administration Panel - Edit User Account - CompetitionAdmin RBAC
  In order to change details relating to user accounts' CompetitionAdmin roles
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel

  @javascript
  Scenario: Add a new CompetitionAdmin role to an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/8" 
	And I click the ".useraccount-rbacrole-add" element
	And I click the "#AddRoleAssignmentButtonMenu a[data-key=competition_admin]" element
	And I wait up to "5000" ms for "$('#RoleAssignmentContainer .rbac-assignment-competitionadmin').length == 1"
	Then I should see an "#RoleAssignmentContainer .rbac-assignment-competitionadmin" element
	And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/8"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements

  @javascript
  Scenario: Add competitions to an existing CompetitionAdmin role of an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/8"
    And I should see 2 "#RoleAssignmentContainer .rbac-assignment" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin" elements
	And I click the ".rbac-assignment-addcompetition" element
	And I click the ".rbac-assignment-addcompetition" element 
	Then I should see 2 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements
	Given I select "Test Competition #2" from "roleAssignments[1][managedCompetitions][1]"
	Given I select "Test Competition #1" from "roleAssignments[1][managedCompetitions][2]"
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/8"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin" elements
    And I should see 2 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on a competition in CompetitionAdmin will drop the competition
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/8" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=2] a.dropcompetition" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=2]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/8"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on all competitions in CompetitionAdmin will empty the collection
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/8" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=1] a.dropcompetition" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid=1]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/8"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin table > tbody > tr[data-competitionid]" elements
  
  @javascript
  Scenario: Clicking the trash can icon on a CompetitionAdmin role will drop the role
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/8" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-competitionadmin a.rbac-assignment-delete" element
	Then I should not see an "#RoleAssignmentContainer .rbac-assignment-competitionadmin" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/8"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-competitionadmin" elements
