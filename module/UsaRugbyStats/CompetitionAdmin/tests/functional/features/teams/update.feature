Feature: Team Administration Panel - Update Team
  In order to create a team
  As a super_admin user
  I need to be able to interact with the Add New Team section of the Team Administration panel
      
  @javascript
  Scenario: Administrator can update a team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"	
    When I fill in the following:
       | team[remoteId] | 654321 |
       | team[name] | Behat Team Updated |
       | team[email] | me@behatteam.com |
       | team[website] | http://behatteam.com |
       | team[facebookHandle] | behatteamfacebook |
       | team[twitterHandle] | behatteamtwitter |
    And select "Test Union #1" from "team[union]"
    # Add an Administrator
    And I click the "a.team-admins-add" element
    And select "Console Administrator" from "administrators[1][account]"
    # Add a Member
    And I click the "a.team-members-add" element
    And select "Member Two" from "members[1][account]"
    And select "Grace Period" from "members[1][membershipStatus]"
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The team was updated successfully!"
    And I should see "Update Team"
    And the "team[remoteId]" field should contain "654321"
    And the "team[name]" field should contain "Behat Team Updated"
    And the "team[union]" field should contain "1"
    And the "team[email]" field should contain "me@behatteam.com"
    And the "team[website]" field should contain "http://behatteam.com"
    And the "team[facebookHandle]" field should contain "behatteamfacebook"
    And the "team[twitterHandle]" field should contain "behatteamtwitter"
    And the "administrators[0][account]" field should contain "6"
    And the "administrators[1][account]" field should contain "1"
    And the "members[0][account]" field should contain "13"
    And the "members[0][membershipStatus]" field should contain "2"
    And the "members[1][account]" field should contain "14"
    And the "members[1][membershipStatus]" field should contain "3"

  @javascript
  Scenario: Administrator can drop administrators from team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"
    And I click the ".team-admins tr[data-recordid=11] a.dropadmin" element
    And I click the ".team-admins tr[data-recordid=27] a.dropadmin" element
    Then I should not see an ".team-admins table tr[data-recordid]" element
    Given I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The team was updated successfully!"
    And I should see "Update Team"
    And I should not see an ".team-admins table tr[data-recordid]" element

  @javascript
  Scenario: Administrator can drop members from team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"
    And I click the ".team-members tr[data-recordid=1] a.dropmember" element
    And I click the ".team-members tr[data-recordid=2] a.dropmember" element
    Then I should not see an ".team-members table tr[data-recordid]" element
    Given I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The team was updated successfully!"
    And I should see "Update Team"
    And I should not see an ".team-members table tr[data-recordid]" element
    
  @javascript
  Scenario: Name is required when updating a team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"	
    When I fill in the following:
       | team[name] |  |
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "Value is required and can't be empty"
            
  @javascript
  Scenario: Name must be unique when updating a team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"    
    When I fill in the following:
       | team[name] | Test Team #1 |
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "There is already another object matching 'array ( 'name' => 'Test Team #1', )'"
    
  @javascript
  Scenario: Union is required when updating a team
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"
    And select "No Union Specified" from "team[union]"	
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The input was not found in the haystack"
                    
  @javascript
  Scenario: Email Address must be valid if specified
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"    
    When I fill in the following:
       | team[email] | foo |
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The input is not a valid email address. Use the basic format local-part@hostname"
    
  @javascript
  Scenario: Team Website must be valid if specified
    Given I am authenticated as a super administrator
    And I go to "/admin/team/edit/9"
    When I fill in the following:
       | team[website] | foo |
    And I press "Save Changes"
    Then I should be on "/admin/team/edit/9"
    And I should see "The input does not appear to be a valid Uri"