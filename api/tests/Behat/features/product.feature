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

  Scenario: Update existing product as admin
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "name": "Iphone 13",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "submittedBy": "/users/{user_1.id}",
        "status": "IN REVIEW"
      }
      """
    When I request "PATCH" "/products/{product_1.id}"
    Then the response status code should be 200
    Then the "name" property should equal "Iphone 13"

  Scenario: Update existing product using wrong payload
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      dzadazdazdada
      """
    When I request "PATCH" "/products/{product_1.id}"
    Then the response status code should be 400

  Scenario: Update existing product as user
    Given a user with role "User"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "name": "Iphone 13",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "submittedBy": "/users/{user_1.id}",
        "status": "IN REVIEW"
      }
      """
    When I request "PATCH" "/products/{product_1.id}"
    Then the response status code should be 403

  Scenario: Update existing product as seller
    Given a user with role "Seller"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      {
        "name": "Iphone 13",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "submittedBy": "/users/{user_1.id}",
        "status": "IN REVIEW"
      }
      """
    When I request "PATCH" "/products/{product_1.id}"
    Then the response status code should be 200
    Then the "name" property should equal "Iphone 13"

  Scenario: Update non existing product
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
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
    When I request "PATCH" "/products/99999"
    Then the response status code should be 404

  Scenario: Replace existing product as admin
    Given a user with role "Admin"
    Given I set the "Accept" header to "application/ld+json"
    When I set payload
      """
      {
        "name": "Iphone 13",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "status": "IN REVIEW"
      }
      """
    When I request "PUT" "/products/{product_1.id}"
    Then the response status code should be 200
    Then the "name" property should equal "Iphone 13"
    Then the "price" property should equal 50

  Scenario: Replace existing product using wrong data
    Given a user with role "Admin"
    Given I set the "Content-Type" header to "application/merge-patch+json"
    When I set payload
      """
      """
    When I request "PUT" "/products/{product_1.id}"
    Then the response status code should be 415

  Scenario: Replace existing product as user
    Given a user with role "User"
    Given I set the "Accept" header to "application/ld+json"
    When I set payload
      """
      {
        "name": "Iphone 12",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "status": "IN REVIEW"
      }
      """
    When I request "PUT" "/products/{product_1.id}"
    Then the response status code should be 403

  Scenario: Replace existing product as seller
    Given a user with role "Seller"
    Given I set the "Accept" header to "application/ld+json"
    When I set payload
      """
      {
        "name": "Iphone 13",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "status": "IN REVIEW"
      }
      """
    When I request "PUT" "/products/{product_1.id}"
    Then the response status code should be 200
    Then the "name" property should equal "Iphone 13"
    Then the "price" property should equal 50

  Scenario: Replace non existing product
    Given a user with role "Admin"
    Given I set the "Accept" header to "application/ld+json"
    When I set payload
      """
      {
        "name": "Iphone 12",
        "price": 50,
        "description": "I broke my Iphone while riding my poney",
        "status": "IN REVIEW"
      }
      """
    When I request "PUT" "/products/99999"
    Then the response status code should be 404

  Scenario: Delete existing product as admin
    Given a user with role "Admin"
    When I request "DELETE" "/products/{product_1.id}"
    Then the response status code should be 204

  Scenario: Delete existing product as user
    Given a user with role "User"
    When I request "DELETE" "/products/{product_1.id}"
    Then the response status code should be 403

  Scenario: Delete existing product as seller
    Given a user with role "Seller"
    When I request "DELETE" "/products/{product_1.id}"
    Then the response status code should be 204

  Scenario: Delete non existing product
    Given a user with role "Admin"
    When I request "DELETE" "/products/99999"
    Then the response status code should be 404
