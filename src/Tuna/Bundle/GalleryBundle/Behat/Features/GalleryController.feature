Feature: As developer i want to test Gallery

    Scenario: Add new image to gallery
        Given I have a kernel instance
        Given there is a gallery:
            | title     |
            | Gallery 1 |
        When I go to "/admin/gallery/1/edit"
        Then the "#thecodeine_gallerybundle_gallery" element should contain "thecodeine_gallerybundle_gallery_items"
        And the "#thecodeine_gallerybundle_gallery_items" element should contain "thecodeine_gallerybundle_gallery_items_0"
        And the "#thecodeine_gallerybundle_gallery_items_0" element should contain "thecodeine_gallerybundle_gallery_items_0_type"
        And the "#thecodeine_gallerybundle_gallery_items_0" element should contain "thecodeine_gallerybundle_gallery_items_0_position"
        And the "#thecodeine_gallerybundle_gallery_items_0" element should contain "thecodeine_gallerybundle_gallery_items_0_image"
        And the "#thecodeine_gallerybundle_gallery_items_0" element should contain "thecodeine_gallerybundle_gallery_items_0_translations_defaultLocale_pl_name"
        And the "#thecodeine_gallerybundle_gallery_items_0" element should contain "thecodeine_gallerybundle_gallery_items_0_translations_translations_en_name"
        And the "#thecodeine_gallerybundle_gallery_items" element should contain "thecodeine_gallerybundle_gallery_items_1"
        And the "#thecodeine_gallerybundle_gallery_items_1" element should contain "thecodeine_gallerybundle_gallery_items_1_type"
        And the "#thecodeine_gallerybundle_gallery_items_1" element should contain "thecodeine_gallerybundle_gallery_items_1_position"
        And the "#thecodeine_gallerybundle_gallery_items_1" element should contain "thecodeine_gallerybundle_gallery_items_1_video"
        And the "#thecodeine_gallerybundle_gallery_items_1" element should contain "thecodeine_gallerybundle_gallery_items_1_translations_defaultLocale_pl_name"
        And the "#thecodeine_gallerybundle_gallery_items_1" element should contain "thecodeine_gallerybundle_gallery_items_1_translations_translations_en_name"
        When I add image to gallery "1"
        When I go to "/admin/gallery/1/edit"
        And the "#thecodeine_gallerybundle_gallery_items" element should contain "thecodeine_gallerybundle_gallery_items_2"
        And the "#thecodeine_gallerybundle_gallery_items_2" element should contain "thecodeine_gallerybundle_gallery_items_2_type"
        And the "#thecodeine_gallerybundle_gallery_items_2" element should contain "thecodeine_gallerybundle_gallery_items_2_position"
        And the "#thecodeine_gallerybundle_gallery_items_2" element should contain "thecodeine_gallerybundle_gallery_items_2_image"
        And the "#thecodeine_gallerybundle_gallery_items_2" element should contain "thecodeine_gallerybundle_gallery_items_2_translations_defaultLocale_pl_name"
        And the "#thecodeine_gallerybundle_gallery_items_2" element should contain "thecodeine_gallerybundle_gallery_items_2_translations_translations_en_name"
        When I add video to gallery "1"
        When I go to "/admin/gallery/1/edit"
        And the "#thecodeine_gallerybundle_gallery_items" element should contain "thecodeine_gallerybundle_gallery_items_3"
        And the "#thecodeine_gallerybundle_gallery_items_3" element should contain "thecodeine_gallerybundle_gallery_items_3_type"
        And the "#thecodeine_gallerybundle_gallery_items_3" element should contain "thecodeine_gallerybundle_gallery_items_3_position"
        And the "#thecodeine_gallerybundle_gallery_items_3" element should contain "thecodeine_gallerybundle_gallery_items_3_video"
        And the "#thecodeine_gallerybundle_gallery_items_3" element should contain "thecodeine_gallerybundle_gallery_items_3_translations_defaultLocale_pl_name"
        And the "#thecodeine_gallerybundle_gallery_items_3" element should contain "thecodeine_gallerybundle_gallery_items_3_translations_translations_en_name"
        And the response status code should be 200
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"
        When I go to "/admin/gallery/1/show"
        Then I should see 4 "li" elements
        And I should see 2 "iframe" element
        And I should see 2 "img" element

    Scenario: Try to get non exist gallery
        Given I have a kernel instance
        When I go to "/admin/image/add/gallery/1"
        Then the response status code should be 404
        And the header "Content-Type" should be equal to "text/html; charset=UTF-8"
