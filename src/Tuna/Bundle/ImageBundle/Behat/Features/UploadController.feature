Feature: As a develober I need to test UploadController actions
  Scenario: Test UploadController@request
    Given I have a kernel instance
    When I go to "/admin/image/upload/request"
    Then I should see "File"
    And I attach the file "test.jpeg" to "the_codeine_image_request[file]"
    And I press "the_codeine_image_request[save]"
    And the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And I should have file in uploads dir
    And the response should contain "image"
    And the response should contain "\"id\":1"
    And the response should contain "\"url\":"

  Scenario: Test UploadController@remote
    Given I have a kernel instance
    When I go to "/admin/image/upload/remote"
    Then I should see "Url"
    And I fill in "the_codeine_image_remote[url]" with "http://127.0.0.1/test.jpeg"
    And I press "the_codeine_image_remote[save]"
    And the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And I should have file in uploads dir
    And the response should contain "image"
    And the response should contain "\"id\":1"
    And the response should contain "\"url\":"