Feature: Simple calculator
  As a user
  I want to add two numbers So that I can see their sum
  Scenario: Add two numbers using the plus operator Given I am on the calculator page
    And I have entered "5" into the first number field
    And I have entered "3" into the second number field And I have selected the "+" operator
    When I submit the form
    Then I should see "8" displayed as the result

  Scenario: Multiply two numbers using the multiplication operator
    Given I am on the calculator page
    And I have entered "6" into the first number field
    And I have entered "4" into the second number field
    And I have selected the "*" operator
    When I submit the form
    Then I should see "24" displayed as the result

  Scenario: Divide two numbers using the division operator
    Given I am on the calculator page
    And I have entered "15" into the first number field
    And I have entered "3" into the second number field
    And I have selected the "/" operator
    When I submit the form
    Then I should see "5" displayed as the result