Feature: User

  Background:
    Given The fixtures files
      | address |
      | cart    |
      | product |
      | order   |
      | user    |

  Scenario: Insert new user and duplicate
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
    When I request "POST" "/users"
    Then the response status code should be 500

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
        "username": "admin",
        "password": "admin",
        "email": "admin@gmail.com"
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

  Scenario: Get list of users
    When I request "GET" "/users"
    Then the response status code should be 200
    And The "content-type" header response should exist
    And The "content-type" header response should be "application/ld+json; charset=utf-8"

  Scenario: Get restricted data
    When I request "GET" "/users/9999"
    Then the response status code should be 401

  Scenario: Get a single user as admin
    Given a user with role "Admin"
    When I request "GET" "/users/{admin.id}"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  Scenario: Get a single user as user
    Given a user with role "User"
    When I request "GET" "/users/{user_1.id}"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  Scenario: Get a single user as seller
    Given a user with role "Seller"
    When I request "GET" "/users/{seller.id}"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  Scenario: Get non existing user
    Given a user with role "Admin"
    When I request "GET" "/users/9999"
    Then the response status code should be 404

  # Test Patch /users
  Scenario: Update existing user as admin
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1"
      }
      """
    When I request "PATCH" "/users/{user_1.id}"
    Then the response status code should be 200
    Then the "username" property should equal "username1"

  Scenario: Update existing user using wrong payload
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      dzadazdazdada
      """
    When I request "PATCH" "/users/{user_1.id}"
    Then the response status code should be 400

  Scenario: Update existing user as user
    Given a user with role "User"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1"
      }
      """
    When I request "PATCH" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Update existing user as seller
    Given a user with role "Seller"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1"
      }
      """
    When I request "PATCH" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Update non existing user
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1"
      }
      """
    When I request "PATCH" "/users/99999"
    Then the response status code should be 404

  # Test PUT /users
  Scenario: Replace existing user as admin
    Given a user with role "Admin"
    Given I set the "Accept" header to "application/ld+json"
    When I set payload
      """
      {
        "username": "username1",
        "password": "password1",
        "email": "username1@gmail.com"
      }
      """
    When I request "PUT" "/users/{user_1.id}"
    Then the response status code should be 200
    Then the "username" property should equal "username1"
    Then the "password" property should equal "password1"
    Then the "email" property should equal "username1@gmail.com"

  Scenario: Replace existing user using wrong data
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      """
    When I request "PUT" "/users/{user_1.id}"
    Then the response status code should be 415

  Scenario: Replace existing user as user
    Given a user with role "User"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1",
        "password": "password1",
        "email": "username1@gmail.com"
      }
      """
    When I request "PUT" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Replace existing user as seller
    Given a user with role "Seller"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1",
        "password": "password1",
        "email": "username1@gmail.com"
      }
      """
    When I request "PUT" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Replace non existing user
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "username": "username1",
        "password": "password1",
        "email": "username1@gmail.com"
      }
      """
    When I request "PUT" "/users/99999"
    Then the response status code should be 404

  # Test DELETE /users
  Scenario: Delete existing user as admin
    Given a user with role "Admin"
    When I request "DELETE" "/users/{user_1.id}"
    Then the response status code should be 204

  Scenario: Delete existing user as user
    Given a user with role "User"
    When I request "DELETE" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Delete existing user as seller
    Given a user with role "Seller"
    When I request "DELETE" "/users/{user_1.id}"
    Then the response status code should be 403

  Scenario: Delete non existing user
    Given a user with role "Admin"
    When I request "DELETE" "/users/99999"
    Then the response status code should be 404

  # Test GET /users/{id}/products
  Scenario: Get user products as admin
    Given a user with role "Admin"
    When I request "GET" "/users/{user_1.id}/products"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  Scenario: Get user products as user
    Given a user with role "User"
    When I request "GET" "/users/{user_1.id}/products"
    Then the response status code should be 403

  Scenario: Get user products as seller
    Given a user with role "Seller"
    When I request "GET" "/users/{user_1.id}/products"
    Then the response status code should be 403

  Scenario: Get user products from non existing user
    Given a user with role "Admin"
    When I request "DELETE" "/users/99999/products"
    Then the response status code should be 405

  # Test GET /users/{id}/orders
  Scenario: Get user orders as admin
    Given a user with role "Admin"
    When I request "GET" "/users/{user_1.id}/orders"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  Scenario: Get user orders as user
    Given a user with role "User"
    When I request "GET" "/users/{user_1.id}/orders"
    Then the response status code should be 403

  Scenario: Get user orders as seller
    Given a user with role "Seller"
    When I request "GET" "/users/{user_1.id}/orders"
    Then the response status code should be 403

  Scenario: Get user orders from non existing user
    Given a user with role "Admin"
    When I request "GET" "/users/{user_1.id}/orders"
    Then the response status code should be 200

  # Test GET /users/{id}/cart
  Scenario: Get user carts as admin
    Given a user with role "Admin"
    When I request "GET" "/users/{user_1.id}/cart"
    And The "content-type" header response should be "application/ld+json; charset=utf-8"
    Then the response status code should be 200

  @only
  Scenario: Create an order
    Given a user with role "Admin"
    When I set payload
      """
      {
        "cart": "/carts/{cart_1.id}"
      }
      """
    When I request "POST" "/users/{user_1.id}/order"
    Then the response status code should be 201
    Then the "status" property should equal "ORDERED"
    When I request "POST" "/users/{user_1.id}/order"
    Then the response status code should be 500
    When I request "GET" "/carts/{cart_1.id}"
    Then the response status code should be 200
    Then the "price" property should equal "0"
    Then the "products" property should be an empty array

  Scenario: Get user carts as user
    Given a user with role "User"
    When I request "GET" "/users/{user_1.id}/cart"
    Then the response status code should be 403

  Scenario: Get user carts as seller
    Given a user with role "Seller"
    When I request "GET" "/users/{user_1.id}/cart"
    Then the response status code should be 403

  Scenario: Get user carts from non existing user
    Given a user with role "Admin"
    When I request "DELETE" "/users/99999/cart"
    Then the response status code should be 405
