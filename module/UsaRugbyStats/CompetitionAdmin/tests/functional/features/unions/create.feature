Feature: Union Administration Panel - Create Union
  In order to create a new union
  As a super_admin user
  I need to be able to interact with the Add New Union section of the Union Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New Union
    Given I am authenticated as a super administrator
    And I go to "/admin/union/create"
    Then I should not receive an authorization error
    And I should see "Create Union"
    And I should see "Union Details"
      
  @javascript
  Scenario: Administrator can create a new union with no teams
    Given I am authenticated as a super administrator
    And I go to "/admin/union/create"	
    When I fill in the following:
       | union[name] | Behat Union |
    And I press "Create Union"
    Then I should be on "/admin/union/edit/3" 
    And I should see "The union was created successfully!"
    And I should see "Update Union"
    And the "union[name]" field should contain "Behat Union"
      
  @javascript
  Scenario: Name is required when creating a new union
    Given I am authenticated as a super administrator
    And I go to "/admin/union/create"
    When I fill in the following:
       | union[name] |  |
    And I press "Create Union"
    Then I should be on "/admin/union/create"
    And I should see "Value is required and can't be empty"
    And the "union[name]" field should contain ""