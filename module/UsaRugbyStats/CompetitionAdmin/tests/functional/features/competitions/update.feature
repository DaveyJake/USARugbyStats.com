Feature: Competition Administration Panel - Update Competition
  In order to update an existing competition
  As a super_admin user
  I need to be able to interact with the Add New Competition section of the Competition Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New Competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1"
    Then I should not receive an authorization error
    And I should see "Test Competition #1"
    And I should see "Competition Details"
    
  @javascript
  Scenario: Administrator can save form as-is
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1" 
    And I press "Save Changes"
    Then I should be on "/admin/competition/edit/1" 
    And I should see "The competition was updated successfully!"
    
  @javascript
  Scenario: Administrator can change competition details
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1"	
    When I fill in the following:
       | competition[name]      | Test Competition 123 |
       | competition[type]      | P |
       | competition[variant]   | 7s |
    And I press "Save Changes"
    Then I should be on "/admin/competition/edit/1" 
    And I should see "The competition was updated successfully!"
    And the "competition[name]" field should contain "Test Competition 123"
    And the "competition[type]" field should contain "P"
    And the "competition[variant]" field should contain "7s"
             
  @javascript
  Scenario: Administrator can change competition details
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1" 
    When I fill in the following:
       | competition[name]      | Test Competition #1 |
       | competition[type]      | L |
       | competition[variant]   | 7s |
    And I press "Save Changes"
    Then I should be on "/admin/competition/edit/1" 
    And I should see "The competition was updated successfully!"
    And the "competition[name]" field should contain "Test Competition #1"
    And the "competition[type]" field should contain "L"
    And the "competition[variant]" field should contain "7s"
       
  @javascript
  Scenario: Administrator cannot change competition name to same name as an existing competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1"	
    When I fill in the following:
       | competition[name]      | Behat Competition |
    And I press "Save Changes"
    Then I should be on "/admin/competition/edit/1"
    And I should see "There is already another object matching"

  @javascript
  Scenario: Name is required when updating a competition
    Given I am authenticated as a super administrator
    And I go to "/admin/competition/edit/1"
    When I fill in the following:
       | competition[name] |  |
    And I press "Save Changes"
    Then I should be on "/admin/competition/edit/1"
    And I should see "Value is required and can't be empty"
    And the "competition[name]" field should contain ""
