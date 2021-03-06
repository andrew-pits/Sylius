@metadata
Feature: Metadata management
  In order to manage metadata on my store
  As a store owner
  I want to have easy and intuitive access to managing metadata

  Background:
    Given store has default configuration
    And there are following options:
      | code | name          | presentation | values                    |
      | TSC  | T-Shirt color | Color        | Red[R], Blue[B], Green[G] |
    And the following products exist:
      | code | name           | price | options       |
      | STS  | Super T-Shirt  | 19.99 | T-Shirt color |
    And product "Super T-Shirt" is available in all variations
    And I am logged in as administrator

  Scenario: Accessing specific product variant metadata customization page
    Given I am on the page of product "Super T-Shirt"
    When I click "Customize metadata" near "Color: Red"
    Then I should be customizing specific product variant metadata
