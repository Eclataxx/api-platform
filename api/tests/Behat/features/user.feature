Feature: User

  Background:
    Given The fixtures files
      | user    |
      | cart    |
      | product |
      | address |
      | order   |
#Test Post /users
  Scenario: Insert new user
    When I set payload
            """
            {
                "username": "test1",
                "password": "test",
                "email": "test1@gmail.com"
            }
            """
    When I request "POST" "/users"
    Then the response status code should be 201

  Scenario: Insert empty user
    When I set payload
          """
          {}
          """
    When I request "POST" "/users"
    Then the response status code should be 500

  Scenario: Insert existing user
    When I set payload
          """
          {
            "username": "exist"
            "email": "iexist@myges.fr"
            "email": "test1@gmail.com"
          }
          """
    When I request "POST" "/users"
    Then the response status code should be 500

  Scenario: Insert wrong user - password
    When I set payload
          """
          {
            "username": "test1",
            "email": "test1@gmail.com"
          }
          """
    When I request "POST" "/users"
    Then the response status code should be 500

  Scenario: Insert wrong user - username
    When I set payload
          """
          {
            "password": "test",
            "email": "test1@gmail.com"
          }
          """
    When I request "POST" "/users"
    Then the response status code should be 500

  Scenario: Insert wrong user - email
    When I set payload
          """
          {
            "username": "test1",
            "password": "test",
          }
          """
    When I request "POST" "/users"
    Then the response status code should be 400

#Test GET /users
  Scenario: Get list of users
    When I request "GET" "/users"
    Then the response status code should be 200
    And The "content-type" header response should exist
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    #Then I add to reference whith "usersList"

  Scenario: Get restricted data without JWT
    When I request "GET" "/users/9999"
    Then the response status code should be 401

  Scenario: Get restricted data without JWT
    When I set payload
          """
          {
            "password": "test2",
            "email": "test1@gmail.com"
          }
          """
    When I request "PATCH" "/users/9999"
    Then the response status code should be 401
