@addressing
Feature: Countries management - enabling and disabling countries
  In order to set which countries are served by my store
  As a store owner
  I want to set if given country is enabled or not

  Scenario: Enabling country
     Given there is a disabled country "France"
       And I am on the country index page
      When I click "Enable" near "France"
      Then I should see country "France" as enabled

  Scenario: Disabling country
     Given there is an enabled country "France"
       And I am on the country index page
      When I click "Disable" near "France"
      Then I should see country "France" as disabled
