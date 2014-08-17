Feature: Account Administration Panel - Edit User Account - UnionAdmin RBAC
  In order to change details relating to user accounts' UnionAdmin roles
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel

  @javascript
  Scenario: Add a new UnionAdmin role to an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the ".useraccount-rbacrole-add" element
	And I click the "#AddRoleAssignmentButtonMenu a[data-key=union_admin]" element
	And I wait up to "5000" ms for "$('#RoleAssignmentContainer .rbac-assignment-unionadmin').length == 1"
	Then I should see an "#RoleAssignmentContainer .rbac-assignment-unionadmin" element
	And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements

  @javascript
  Scenario: Add unions to an existing UnionAdmin role of an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15"
    And I should see 2 "#RoleAssignmentContainer .rbac-assignment" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin" elements
	And I click the ".rbac-assignment-addunion" element
	And I click the ".rbac-assignment-addunion" element 
	Then I should see 2 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements
	Given I select "Test Union #2" from "roleAssignments[1][managedUnions][1]"
	Given I select "Test Union #1" from "roleAssignments[1][managedUnions][2]"
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin" elements
    And I should see 2 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on a union in UnionAdmin will drop the union
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=2] a.dropunion" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=2]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=2]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on all unions in UnionAdmin will empty the collection
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=1] a.dropunion" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid=1]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin table > tbody > tr[data-unionid]" elements
  
  @javascript
  Scenario: Clicking the trash can icon on a UnionAdmin role will drop the role
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-unionadmin a.rbac-assignment-delete" element
	Then I should not see an "#RoleAssignmentContainer .rbac-assignment-unionadmin" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-unionadmin" elements
