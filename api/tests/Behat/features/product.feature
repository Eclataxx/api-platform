Feature: Product

    Background:
        Given the fixtures files
            | address |
            | cart    |
            | product |
            | order   |
            | user    |

    Scenario: Insert new product while not authenticated
        When I set payload
            """
            {
                "name": "Iphone 12",
                "price": 50,
                "description": "I broke my Iphone while riding my poney",
                "status": "IN REVIEW"
            }
            """
        When I request "POST" "/products"
        Then the response status code should be 401

    Scenario: Insert new product while authenticated
        Given a user with role "Seller"
        When I set payload
            """
            {
                "name": "Iphone 12",
                "price": 50,
                "description": "I broke my Iphone while riding my poney",
                "submittedBy": "/users/{user_1.id}",
                "status": "IN REVIEW"
            }
            """
        When I request "POST" "/products"
        Then the response status code should be 201

    Scenario: Get list of products
        When I request "GET" "/products"
        Then the response status code should be 200
        And The "content-type" header response should exist
        And The "content-type" header response should be "application/ld+json; charset=utf-8"

    Scenario: Get list of validated products
        When I request "GET" "/products?status=VALIDATED"
        Then the response status code should be 200
        And The "content-type" header response should exist
        And The "content-type" header response should be "application/ld+json; charset=utf-8"
