Feature: Address
    Background:
        Given The fixtures files
            | address |
            | cart    |
            | product |
            | order   |
            | user    |

    Scenario: Get list of addresses
        Given a user with role "User"
        When I request "GET" "/addresses"
        Then the response status code should be 200
        And The "content-type" header response should be "application/ld+json; charset=utf-8"

    Scenario: Get list of addresses while unauthenticated
        When I request "GET" "/addresses"
        Then the response status code should be 401

    Scenario: Insert a new address
        Given a user with role "User"
        When I set payload
            """
            {
                "city": "Saint-Denis",
                "country": "France",
                "postalCode": "93200",
                "streetAddress": "2 rue Ampère"
            }
            """
        When I request "POST" "/addresses"
        Then the response status code should be 201
        And The "content-type" header response should be "application/ld+json; charset=utf-8"

    Scenario: Unauthenticated insert
        When I set payload
            """
            {
                "city": "Saint-Denis",
                "country": "France",
                "postalCode": "93200",
                "streetAddress": "2 rue Ampère"
            }
            """
        When I request "POST" "/addresses"
        Then the response status code should be 401

    Scenario: Get an address
        Given a user with role "User"
        When I request "GET" "/addresses/{address_1.id}"
        And The "content-type" header response should be "application/ld+json; charset=utf-8"
        Then the response status code should be 200

    Scenario: Get an address while unauthenticated
        When I request "GET" "/addresses/{address_1.id}"
        Then the response status code should be 401

    Scenario: Delete an address
        Given a user with role "User"
        When I request "DELETE" "/addresses/{address_1.id}"
        Then the response status code should be 204

    Scenario: Delete an address while unauthenticated
        When I request "DELETE" "/addresses/{address_1.id}"
        Then the response status code should be 401

    Scenario: Put an address
        Given a user with role "User"
        Given I set the "Accept" header to "application/ld+json"
        When I set payload
            """
            {
                "city": "Paris",
                "country": "France",
                "postalCode": "75000",
                "streetAddress": "2 rue de Paris"
            }
            """
        When I request "PUT" "/addresses/{address_1.id}"
        Then the response status code should be 200
        Then the "city" property should equal "Paris"
        Then the "country" property should equal "France"
        Then the "postalCode" property should equal "75000"
        Then the "streetAddress" property should equal "2 rue de Paris"

    Scenario: Put an address
        Given a user with role "User"
        Given I set the "Accept" header to "application/ld+json"
        When I set payload
            """
            {
                "city": "Paris",
                "country": "France",
                "postalCode": "75000",
                "streetAddress": "2 rue de Paris"
            }
            """
        When I request "PUT" "/addresses/{address_1.id}"
        Then the response status code should be 200
        Then the "city" property should equal "Paris"
        Then the "country" property should equal "France"
        Then the "postalCode" property should equal "75000"
        Then the "streetAddress" property should equal "2 rue de Paris"

    Scenario: Put an address while unauthenticated
        When I request "PUT" "/addresses/{address_1.id}"
        Then the response status code should be 401

    Scenario: Patch an address
        Given a user with role "User"
        Given I set the "Content-Type" header to "application/merge-patch+json"
        When I set payload
            """
            {
                "city": "Paris",
                "country": "France"
            }
            """
        When I request "PATCH" "/addresses/{address_1.id}"
        Then the response status code should be 200
        Then the "city" property should equal "Paris"
        Then the "country" property should equal "France"
        Then the "postalCode" property should exist
        Then the "streetAddress" property should exist

    Scenario: Patch an address while unauthenticated
        When I request "PATCH" "/addresses/{address_1.id}"
        Then the response status code should be 401
