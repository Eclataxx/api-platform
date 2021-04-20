Feature: REST
    Scenario: Insert new user
        When I have The Payload
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
        #Then I add to reference whith "usersList"

    Scenario: Get restricted data without JWT
        When I request "GET" "/users/9999"
        Then the response status code should be 401
