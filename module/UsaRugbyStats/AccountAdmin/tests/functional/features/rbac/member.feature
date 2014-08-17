Feature: Account Administration Panel - Edit User Account - Member RBAC
  In order to change details relating to user accounts' Member roles
  As a super_admin user
  I need to be able to interact with the edit user section of the Account Administration panel
        
  @javascript
  Scenario: Add memberships to an existing Member role of an existing user
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/14"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member" elements
	And I click the ".rbac-assignment-member-membership-add" element
	And I click the ".rbac-assignment-member-membership-add" element
	Then I should see 2 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index]" elements
	Given I select "Test Team #1" from "roleAssignments[0][memberships][0][team]"
	And I select "Current" from "roleAssignments[0][memberships][0][membershipStatus]"
    Given I select "Test Team #2" from "roleAssignments[0][memberships][1][team]"
    And I select "Grace Period" from "roleAssignments[0][memberships][1][membershipStatus]"
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/14"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index=0]" elements
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index=1]" elements
    And I should see that "roleAssignments[0][memberships][0][team]" field has value "1"
    And I should see that "roleAssignments[0][memberships][0][membershipStatus]" field has value "2"

  @javascript
  Scenario: Clicking the trash can icon on a team in Member will drop the membership
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/14" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index=1] a.rbac-assignment-member-membership-remove" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index=1]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/14"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index]" elements
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index=1]" elements

  @javascript
  Scenario: Clicking the trash can icon on all teams in Member will empty the collection
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/14" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-member-membership a.rbac-assignment-member-membership-remove" element
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index]" elements
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/14"
    And I should see 0 "#RoleAssignmentContainer .rbac-assignment-member-membership[data-index]" elements
  
  @javascript
  Scenario: Clicking the trash can icon on a Member role will not drop the role
    Given I am authenticated as a super administrator
    And I go to "/admin/user/edit/14" 
	And I click the "#RoleAssignmentContainer .rbac-assignment-member a.rbac-assignment-delete" element
	Then I should not see an "#RoleAssignmentContainer .rbac-assignment-member" element
    And I press "submit"
    Then I should be on "/admin/user/list" 
    Given I go to "/admin/user/edit/14"
    And I should see 1 "#RoleAssignmentContainer .rbac-assignment-member" elements
