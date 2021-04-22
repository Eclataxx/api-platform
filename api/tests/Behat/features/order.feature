@orders
Feature: Order
  Background:
    Given the fixtures files
      | address |
      | cart    |
      | product |
      | order   |
      | user    |

  Scenario: Create a new order while not authenticated
    When I set payload
      """
      {
        "price": 99,
        "date": "2020-04-22T20:57:56.387Z",
        "status": "string",
        "products": ["/products/{product_1.id}"]
      }
      """
    When I request "POST" "/orders"
    Then the response status code should be 401

  Scenario: Create a new order while authenticated
    Given a user with role "User"
    When I set payload
      """
      {
        "price": 99,
        "date": "2021-04-22T20:57:56.387Z",
        "status": "string",
        "products": ["/products/{product_1.id}"],
        "associatedUser": "/users/{custom.id}"
      }
      """
    When I request "POST" "/orders"
    Then the response status code should be 201
