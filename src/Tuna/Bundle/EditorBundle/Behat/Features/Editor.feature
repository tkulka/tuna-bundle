Feature: As a developer I want to test Editor Twig extension

    Scenario: Build simple form with extension TestController@test
        Given I have a kernel instance
        Given I should have editor extension service in container
        When I go to "/admin/editor/test"
        Then the "body" element should contain "form_the_codeine_editor_test"
