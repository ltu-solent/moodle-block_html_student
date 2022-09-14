@block @block_html_student
Feature: Adding and configuring Student HTML blocks
  In order to have custom blocks on a page for Students
  As admin
  I need to be able to create, configure and change Student HTML blocks

  @javascript
  Scenario: Configuring the HTML block with Javascript on
    Given I log in as "admin"
    And I am on site homepage
    When I turn editing mode on
    And I add the "HTML student" block
    And I configure the "(new HTML student block)" block
    And I set the field "Content" to "Static text without a header"
    Then I should see "Block title"
    And I press "Save changes"
    Then I should not see "(new HTML student block)"
    And I configure the "block_html_student" block
    And I set the field "Block title" to "The HTML student block header"
    And I set the field "Content" to "Static text with a header"
    And I press "Save changes"
    And "block_html_student" "block" should exist
    And "The HTML student block header" "block" should exist
    And I should see "Static text with a header" in the "The HTML student block header" "block"

  Scenario: Configuring the HTML block with Javascript off
    Given I log in as "admin"
    And I am on site homepage
    When I turn editing mode on
    And I add the "HTML student" block
    And I configure the "(new HTML student block)" block
    And I set the field "Content" to "Static text without a header"
    And I press "Save changes"
    Then I should not see "(new HTML student block)"
    And I configure the "block_html_student" block
    And I set the field "Block title" to "The HTML student block header"
    And I set the field "Content" to "Static text with a header"
    And I press "Save changes"
    And "block_html_student" "block" should exist
    And "The HTML student block header" "block" should exist
    And I should see "Static text with a header" in the "The HTML student block header" "block"
