Feature: demo

    Scenario: Insert new user
        When I have The Payload
        """
        {
            "email": "test@test.fr",
            "password": "test"
        }
        """
        When I request "POST /users"
        Then the response status code should be 201

    Scenario: Test get list of users
        When I request "GET /users"
        Then the response status code should be 200
        #Then I add to reference whith "usersList"

    Scenario: Test get not found user
        When I request "GET /users/XXX"
        Then the response status code should be 404
