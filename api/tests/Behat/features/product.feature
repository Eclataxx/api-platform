Feature: Product
    Background:
        Given the fixtures files
            | address |
            | cart    |
            | product |
            | order   |
            | user    |

    Scenario: Insert new product without authorization
        When I set payload
            """
            {
                "name": "Iphone 12",
                "price": "50",
                "description": "I broke my Iphone while riding my poney"
            }
            """
        When I request "POST" "/products"
        Then the response status code should be 401
