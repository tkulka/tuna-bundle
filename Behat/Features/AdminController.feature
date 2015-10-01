Feature: As a developer I want to test AdminBundle integration

  Scenario: i try to enter admin area without auth
    When I go to "/admin"
    Then I should see "Ahoy! Let's log in"

  Scenario: i try to log in to admin area with bad credentials
    When I go to "/admin"
    When I fill in the following:
      | _username | bad          |
      | _password | credentials  |
    And I press "_submit"
    Then I should see "Woops! Bad credentials."

  Scenario: i try to log in to admin area
    When I go to "/admin"
    When I fill in the following:
      | _username | admin |
      | _password | admin |
    And I press "_submit"
    Then I should see "Panel"

  @logged-in
  Scenario: as admin i want to see main panel page
    When I go to "/admin"
    Then I should see "Panel"

  @logged-in
  Scenario: as admin i want to see news list
    When I go to "/admin/news/list"
    Then I should see 1 ".admin-list>.table.table-striped.table-hover>tbody>tr" element

  @logged-in
  Scenario: as admin i want to create news
    When I go to "/admin/news/create"
    And I fill in the following:
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][title]    | title_pl    |
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][subTitle] | subTitle_pl |
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][body]     | body_pl     |
    And I press "thecodeine_newsbundle_news[save]"
    And I go to "/admin/news/list"
    And I should see "title_pl"

  @logged-in
  Scenario: as admin i want to edit news
    When I go to "/admin/news/2/edit"
    Then the "thecodeine_newsbundle_news[translations][defaultLocale][pl][title]" field should contain "title_pl"
    And the "thecodeine_newsbundle_news[translations][defaultLocale][pl][subTitle]" field should contain "subTitle_pl"
    And the "thecodeine_newsbundle_news[translations][defaultLocale][pl][body]" field should contain "body_pl"
    When I fill in the following:
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][title]    | title_pl_edit |
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][subTitle] | subTitle_pl_edit |
      | thecodeine_newsbundle_news[translations][defaultLocale][pl][body]     | body_pl_edit  |
    And I press "thecodeine_newsbundle_news[save]"
    And I go to "/admin/news/list"
    And I should see "title_pl_edit"

  @logged-in
  Scenario: as admin i want to see page list
    When I go to "/admin/page/list"
    Then I should see 2 ".admin-list>.table.table-striped.table-hover>tbody>tr" elements

  @logged-in
  Scenario: as admin i want to create new page
    When I go to "/admin/page/create"
    And I fill in the following:
      | thecodeine_pagebundle_page[translations][defaultLocale][pl][title] | title_pl |
      | thecodeine_pagebundle_page[translations][defaultLocale][pl][body]  | body_pl  |
    And I press "thecodeine_pagebundle_page[save]"
    And I go to "/admin/page/list"
    And I should see "title_pl"

  @logged-in
  Scenario: as admin i want to edit page
    When I go to "/admin/page/3/edit"
    Then the "thecodeine_pagebundle_page[translations][defaultLocale][pl][title]" field should contain "title_pl"
    And the "thecodeine_pagebundle_page[translations][defaultLocale][pl][body]" field should contain "body_pl"
    When I fill in the following:
      | thecodeine_pagebundle_page[translations][defaultLocale][pl][title] | title_pl_edit |
      | thecodeine_pagebundle_page[translations][defaultLocale][pl][body]  | body_pl_edit  |
    And I press "thecodeine_pagebundle_page[save]"
    And I go to "/admin/page/list"
    And I should see "title_pl_edit"
