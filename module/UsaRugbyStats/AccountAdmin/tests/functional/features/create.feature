Feature: Account Administration Panel - Create User Account
  In order to create a new user account
  As a super_admin user
  I need to be able to interact with the Add New User section of the Account Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New User
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel
    Then I should not receive an authorization error
    And I should see "Create User"
    And I should see "Basic Account Details"
      
  @javascript
  Scenario: Administrator can create a new user account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | adam+testuser123@lundrigan.ca |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should see "The user was created"
    And I should see "adam+testuser123@lundrigan.ca"

  @javascript
  Scenario: Username is a required field for creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username |  |
       | email | adam+testuser123@lundrigan.ca |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "Value is required and can't be empty"
      
  @javascript
  Scenario: Must provide a unique username when creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | teamadmin |
       | email | adam+testuser123@lundrigan.ca |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "A record matching the input was found"

  @javascript
  Scenario: Email Address is a required field for creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email |  |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "Value is required and can't be empty"
    
  @javascript
  Scenario: Must provide a valid email address when creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | notanemailaddress |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "The input is not a valid email address"
      
  @javascript
  Scenario: Must provide a unique email address when creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | adam+teamadmin@lundrigan.ca |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "A record matching the input was found"
    
  @javascript
  Scenario: Display Name is a required field for creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | adam+testuser123@lundrigan.ca |
       | display_name |  |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "Value is required and can't be empty"
    
  @javascript
  Scenario: Password is a required field for creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | adam+testuser123@lundrigan.ca |
       | display_name | Test User |
       | password |  |
       | passwordVerify | testtest123 |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "Value is required and can't be empty"
        
  @javascript
  Scenario: Password fields must match when creating a new account
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser123 |
       | email | adam+testuser123@lundrigan.ca |
       | display_name | Test User |
       | password | testtest123 |
       | passwordVerify | testtest123## |
    And I press "Register"
    Then I should be on the Add New User page of the Account Administration Panel
    And I should see "The two given tokens do not match"
      
  @javascript
  Scenario: Administrator can create a new user account with a single RBAC role
    Given I am authenticated as a super administrator
    And I navigate to the Add New User page of the Account Administration Panel	
    When I fill in the following:
       | username | testuser456 |
       | email | adam+testuser456@lundrigan.ca |
       | display_name | Test User 2 |
       | password | testtest123 |
       | passwordVerify | testtest123 |
    Then I click the Add Role Assigment dropdown
    And I click the "team_admin" type under the Add Role Assignment dropdown
    And I click the add team button on the Team Administrator dialog
    And I select team "3" from the "1" managedTeam field
    And I click the add team button on the Team Administrator dialog
    And I select team "5" from the "2" managedTeam field
    And I press "Register"
    Then I should see "The user was created"
    And I should see "adam+testuser456@lundrigan.ca"
    Then navigate to the Edit User page of the Account Administration Panel for user with "email" "adam+testuser456@lundrigan.ca"
    And I should see "Role-based Access Control"
    And I should see "Team Administrator"
    And I should see "Test Team #3"
    And I should see "Test Team #5"