Feature: As a develober I need to test NewsController actions

    Scenario: Show list of news (NewsController@list)
        When I go to "/admin/news/list"
        Then I should see "list"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Try to remove exist news (NewsController@delete)
        Given There is a news:
            | title | subTitle | body | slug |
            | aaaaa | bbbbbbbb | cccc | ssss |
        When I go to "/admin/news/list"
        Then I should see "aaaaa"
        When I go to "/admin/news/1/delete"
        Then I should see "list"
        And I should not see "aaaaa"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Try to delete not exist news (NewsController@delete)
        When I go to "/admin/news/1/delete"
        Then the response status code should be 404

    Scenario: Try to edit exist news (NewsController@edit)
        Given There is a news:
            | title | subTitle | body | slug |
            | aaaaa | bbbbbbbb | cccc | ssss |
        When I go to "/admin/news/1/edit"
        Then the "thecodeine_newsbundle_news[translations][defaultLocale][pl][title]" field should contain "aaaaa"
        And the "thecodeine_newsbundle_news[translations][defaultLocale][pl][subTitle]" field should contain "bbbbbbbb"
        And the "thecodeine_newsbundle_news[translations][defaultLocale][pl][body]" field should contain "cccc"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: I can edit news with attachment and gallery
        Given There is a news with attachment and gallery:
            | title | body | slug |
            | aaaaa | cccc | ssss |
        When I go to "/admin/news/1/edit"
        Then print last response
        Then I should see "edit aaaaa"
        And the "body" element should contain "thecodeine_newsbundle_news_gallery_items_0"
        And the "body" element should contain "thecodeine_newsbundle_news_attachments_0"

    Scenario: Try to edit not exist news (NewsController@edit)
        When I go to "/admin/news/1/edit"
        Then the response status code should be 404

    Scenario: Try to add attachment to existing news (NewsController@addAttachment)
        Given There is a news:
            | title | subTitle | body | slug |
            | aaaaa | bbbbbbbb | cccc | ssss |
        When I go to "/admin/news/1/attachment/add"
        Then I should see "add attachment"
        And the "body" element should contain "form name=\"thecodeine_newsbundle_attachment\""
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Try to add attachment not exist news (NewsController@addAttachment)
        When I go to "/admin/news/1/attachment/add"
        Then the response status code should be 404

    Scenario: Try to show existing news (NewsController@show)
        Given There is a news:
            | title | subTitle | body | slug |
            | aaaaa | bbbbbbbb | cccc | ssss |
        When I go to "/pl/news/1"
        Then I should see "aaaaa"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"

    Scenario: Try to show not exist news (NewsController@show)
        When I go to "/pl/news/1"
        Then the response status code should be 404

    Scenario: I can add news and see it on list page
        When I go to "/admin/news/create"
        Then the "body" element should contain "form name=\"thecodeine_newsbundle_news\""
        When I fill in the following:
            | thecodeine_newsbundle_news[translations][defaultLocale][pl][title]    | title_pl |
            | thecodeine_newsbundle_news[translations][defaultLocale][pl][subTitle] | sub_pl   |
            | thecodeine_newsbundle_news[translations][defaultLocale][pl][body]     | body_pl  |
            | thecodeine_newsbundle_news[translations][translations][en][title]     | title_en |
            | thecodeine_newsbundle_news[translations][translations][en][subTitle]  | sub_en   |
            | thecodeine_newsbundle_news[translations][translations][en][body]      | body_en  |
        And I press "thecodeine_newsbundle_news[save]"
        And I go to "/pl/news/1"
        Then I should see "title_pl"
        And I go to "/en/news/1"
        Then I should see "title_en"
        And I go to "/admin/news/1/edit"
        Then I should see "edit title_pl"
