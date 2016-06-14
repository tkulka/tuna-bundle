Feature: As a develober I need to test PageController actions

    Scenario: Test PageController@list
        When I go to "/admin/page/list"
        Then I should see "list"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Test PageController@edit
        When I go to "/admin/page/1/edit"
        Then the response status code should be 404
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: I can add new page and see it on list page
        When I go to "/admin/page/create"
        Then the "body" element should contain "form name=\"thecodeine_pagebundle_page\""
        When I fill in the following:
            | thecodeine_pagebundle_page[translations][defaultLocale][pl][title] | title_pl |
            | thecodeine_pagebundle_page[translations][defaultLocale][pl][body]  | body_pl  |
            | thecodeine_pagebundle_page[translations][translations][en][title]  | title_en |
            | thecodeine_pagebundle_page[translations][translations][en][body]   | body_en  |
        And I press "thecodeine_pagebundle_page[save]"
        And I go to "/pl/page/1"
        Then I should see "title_pl"
        And I go to "/en/page/1"
        Then I should see "title_en"
        And I go to "/admin/page/1/edit"
        Then I should see "edit title_pl"

    Scenario: I can edit page with attachment and gallery
        Given There is a page with attachment and gallery:
            | title | body | slug |
            | aaaaa | cccc | ssss |
        When I go to "/admin/page/1/edit"
        Then I should see "edit aaaaa"
        And the "body" element should contain "thecodeine_pagebundle_page_gallery_items_0"
        And the "body" element should contain "thecodeine_pagebundle_page_attachments_0"

    Scenario: I can't add new page with empty pl fields
        When I go to "/admin/page/create"
        Then the "body" element should contain "form name=\"thecodeine_pagebundle_page\""
        When I fill in the following:
            | thecodeine_pagebundle_page[translations][translations][en][title] | title_en |
            | thecodeine_pagebundle_page[translations][translations][en][body]  | body_en  |
        And I press "thecodeine_pagebundle_page[save]"
        And I go to "/admin/page/list"
        Then I should not see "title_en"

    Scenario: I can't see not exist page
        And I go to "/pl/page/1"
        Then the response status code should be 404
        And I go to "/en/page/1"
        Then the response status code should be 404

    Scenario: Try to remove exist page (PageController@delete)
        Given There is a page:
            | title | body | slug |
            | aaaaa | cccc | ssss |
        When I go to "/admin/page/list"
        Then I should see "aaaaa"
        When I go to "/admin/page/1/delete"
        Then I should see "list"
        And I should not see "aaaaa"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Try to delete not exist page (PageController@delete)
        When I go to "/admin/page/1/delete"
        Then the response status code should be 404
