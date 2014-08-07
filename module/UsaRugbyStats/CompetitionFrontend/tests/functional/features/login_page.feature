Feature: CompetitionFrontend User Dashboard Routing
  Upon login
  As an existing user
  I should be redirected to the correct dashboard
  
  @javascript
  Scenario: Super Administrator receives the ZfcAdmin homepage
    Given I go to "/account/login"
    When I fill in the following:
       | identity | superadmin |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/admin"
      
  @javascript
  Scenario: Team Administrator managing a single team receives the TeamController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | teamadmin_single |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/team/1"
      
  @javascript
  Scenario: Team Administrator managing multiple teams receives the Dashboard\TeamAdminController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | teamadmin_multi |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/" 
    And I should see "Test Team #1"
    And I should see "Test Team #2"

  @javascript
  Scenario: Union Administrator managing a single union receives the UnonController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | unionadmin_single |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/union/1"
      
  @javascript
  Scenario: Union Administrator managing multiple unions receives the Dashboard\UnionAdminController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | unionadmin_multi |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/" 
    And I should see "Test Union #1"
    And I should see "Test Union #2"
    
  @javascript
  Scenario: Competition Administrator managing a single competition receives the CompetitionController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | competitionadmin_single |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/competition/1"
      
  @javascript
  Scenario: Competition Administrator managing multiple competitions receives the Dashboard\CompetitionAdminController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | competitionadmin_multi |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/" 
    And I should see "Test Competition #1"
    And I should see "Test Competition #2"
      
  @javascript
  Scenario: Normal User receives their PlayerController page
    Given I go to "/account/login"
    When I fill in the following:
       | identity | memberone |
       | credential | testtest |
    And I press "Sign In"
    Then I should be on "/player/12" 
    And I should see "Player Statistics"
    And I should see "Teams Played For"