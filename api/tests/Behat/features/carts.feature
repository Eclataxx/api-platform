Feature: Cart
  Background:
    Given the fixtures files
      | address |
      | cart    |
      | product |
      | order   |
      | user    |

  Scenario: Create new cart while not authenticated
    When I set payload
            """
            {
                "price": 50,
                "products": ["1", "2"]
            }
            """
    When I request "POST" "/carts"
    Then the response status code should be 401

  Scenario: Insert new cart while authenticated as a User
    Given a user with role "User"
    When I set payload
            """
            {
                "price": 50,
                "products": ["/products/{product_1.id}"]
            }
            """
    When I request "POST" "/carts"
    Then the response status code should be 201

  Scenario: Insert new cart while authenticated as a Seller
    Given a user with role "Seller"
    When I set payload
            """
            {
                "price": 50,
                "products": ["/products/{product_1.id}"]
            }
            """
    When I request "POST" "/carts"
    Then the response status code should be 201

  Scenario: Insert new cart while authenticated as a Admin
    Given a user with role "Admin"
    When I set payload
            """
            {
                "price": 50,
                "products": ["/products/{product_1.id}"]
            }
            """
    When I request "POST" "/carts"
    Then the response status code should be 201

  Scenario: Get list of all carts while not authenticated
    When I request "GET" "/carts"
    Then the response status code should be 401

  Scenario: Get list of all carts while being a Admin
    Given a user with role "Admin"
    When I request "GET" "/carts"
    Then the response status code should be 200
    And The "content-type" header response should be "application/ld+json; charset=utf-8"

  Scenario: Get non existing cart
    Given a user with role "Admin"
    When I request "GET" "/carts/0"
    Then the response status code should be 404

  Scenario: Patch a cart with the wrong header
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/based-pog-format+json"
    When I set payload
            """
            {
                "products": ["/products/{product_4.id}"]
            }
            """
    When I request "PATCH" "/carts/{cart_1.id}"
    Then the response status code should be 415


  Scenario: Patch a random cart while not authenticated
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
            """
            {
                "products": ["/products/{product_4.id}"]
            }
            """
    When I request "PATCH" "/carts/{cart_1.id}"
    Then the response status code should be 401

  Scenario: Patch a random cart while authenticated as a User
    Given a user with role "User"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
            """
            {
                "products": ["/products/{product_4.id}"]
            }
            """
    When I request "PATCH" "/carts/{cart_1.id}"
    Then the response status code should be 403

  Scenario: Patch a random cart while authenticated as a Seller
    Given a user with role "Seller"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
            """
            {
                "products": ["/products/{product_4.id}"]
            }
            """
    When I request "PATCH" "/carts/{cart_1.id}"
    Then the response status code should be 403

  Scenario: Patch a random cart while authenticated as a Admin
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
            """
            {
                "products": ["/products/{product_4.id}"]
            }
            """
    When I request "PATCH" "/carts/{cart_1.id}"
    Then the response status code should be 200
