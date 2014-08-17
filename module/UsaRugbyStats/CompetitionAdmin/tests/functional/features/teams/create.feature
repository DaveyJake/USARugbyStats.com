Feature: Team Administration Panel - Create Team
  In order to create a new team
  As a super_admin user
  I need to be able to interact with the Add New Team section of the Team Administration panel
  
  @javascript
  Scenario: Super Administrator (super_admin) is allowed to access Add New Team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"
    Then I should not receive an authorization error
    And I should see "Create Team"
    And I should see "Team Details"
      
  @javascript
  Scenario: Administrator can create a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[remoteId] | 123456 |
       | team[name] | Behat Team |
       | team[email] | me@myteam.com |
       | team[website] | http://myteam.com |
       | team[facebookHandle] | myteamfacebook |
       | team[twitterHandle] | myteamtwitter |
    And select "Test Union #2" from "team[union]"
    # Add an Administrator
    And I click the "a.team-admins-add" element
    And select "Team Administrator Multi" from "administrators[0][account]"
    # Add a Member
    And I click the "a.team-members-add" element
    And select "Member Single" from "members[0][account]"
    And select "Current" from "members[0][membershipStatus]"
    And I press "Create Team"
    Then I should be on "/admin/team/edit/9"
    And I should see "The team was created successfully!"
    And I should see "Update Team"
    And the "team[remoteId]" field should contain "123456"
    And the "team[name]" field should contain "Behat Team"
    And the "team[union]" field should contain "2"
    And the "team[email]" field should contain "me@myteam.com"
    And the "team[website]" field should contain "http://myteam.com"
    And the "team[facebookHandle]" field should contain "myteamfacebook"
    And the "team[twitterHandle]" field should contain "myteamtwitter"
    And the "administrators[0][account]" field should contain "6"
    And the "members[0][account]" field should contain "13"
    And the "members[0][membershipStatus]" field should contain "2"
      
  @javascript
  Scenario: Name is required when creating a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[name] |  |
    And select "Test Union #1" from "team[union]"
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "Value is required and can't be empty"
    And the "team[name]" field should contain ""
    And the "team[union]" field should contain "1"
            
  @javascript
  Scenario: Name must be unique when creating a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"    
    When I fill in the following:
       | team[name] | Behat Team |
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "There is already another object matching 'array ( 'name' => 'Behat Team', )'"
    
  @javascript
  Scenario: Union is required when creating a new team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"	
    When I fill in the following:
       | team[name] | Foobar Bazbat |
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "The input was not found in the haystack"
                    
  @javascript
  Scenario: Email Address must be valid if specified
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"    
    When I fill in the following:
       | team[email] | foo |
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "The input is not a valid email address. Use the basic format local-part@hostname"
    
  @javascript
  Scenario: Team Website must be valid if specified
    Given I am authenticated as a super administrator
    And I go to "/admin/team/create"
    When I fill in the following:
       | team[website] | foo |
    And I press "Create Team"
    Then I should be on "/admin/team/create"
    And I should see "The input does not appear to be a valid Uri"