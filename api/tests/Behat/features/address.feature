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
        And The "content-type" header response should exist
        And The "content-type" header response should be "application/ld+json; charset=utf-8"

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
        And The "content-type" header response should exist
        And The "content-type" header response should be "application/ld+json; charset=utf-8"

