Feature: Account Administration Panel - Edit User Account - SuperAdmin RBAC
  In order to change details relating to user accounts' SuperAdmin roles
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel
        
  @javascript
  Scenario: Add a new SuperAdmin role to an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the ".useraccount-rbacrole-add" element
	And I click the "#AddRoleAssignmentButtonMenu a[data-key=team_admin]" element
	And I wait up to "5000" ms for "$('#RoleAssignmentContainer .rbac-assignment-superadmin').length == 1"
	Then I should see an "#RoleAssignmentContainer .rbac-assignment-superadmin" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-superadmin" elements

  @javascript
  Scenario: Clicking the trash can icon on a SuperAdmin role will drop the role
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/15" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-superadmin a.rbac-assignment-delete" element
	Then I should not see an "#RoleAssignmentContainer .rbac-assignment-superadmin" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/15"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-superadmin" elements
