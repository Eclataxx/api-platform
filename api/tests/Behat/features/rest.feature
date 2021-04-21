Feature: REST
    Background:
        Given The fixtures files
            | user |

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

    Scenario: Get list of users
        When I request "GET" "/users"
        Then the response status code should be 200
        And The "content-type" header response should exist
        And The "content-type" header response should be "application/ld+json; charset=utf-8"
    #Then I add to reference whith "usersList"

    Scenario: Get restricted data without JWT
        When I request "GET" "/users/9999"
        Then the response status code should be 401

    Scenario: Insert empty user
      When I have The Payload
          """
          {}
          """
      When I request "POST" "/users"
      Then the response status code should be 500

    Scenario: Insert existing user
      When I have The Payload
          """
          {
            "username": "test1",
            "password": "test",
            "email": "test1@gmail.com"
          }
          """
      When I request "POST" "/users"
      Then the response status code should be 500
