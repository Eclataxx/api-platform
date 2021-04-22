# Feature: User
#   Background:
#     Given The fixtures files
#       | address |
#       | cart    |
#       | product |
#       | order   |
#       | user    |

#   Scenario: Insert new user
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 201

#   Scenario: Insert empty user
#     When I set payload
#       """
#       {}
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 500

#   Scenario: Insert existing user
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 500

#   Scenario: Insert wrong user - password
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "email": "test1@gmail.com"
#       }
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 500

#   Scenario: Insert wrong user - username
#     When I set payload
#       """
#       {
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 500

#   Scenario: Insert wrong user - email
#     When I set payload
#       """
#       {
#       "username": "test1",
#       "password": "test",
#       }
#       """
#     When I request "POST" "/users"
#     Then the response status code should be 400

#   #Test GET /users
#   Scenario: Get list of users
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"

#   Scenario: Get restricted data
#     When I request "GET" "/users/9999"
#     Then the response status code should be 401

#   Scenario: Get list of users then get a single user as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I request "GET" a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get list of users then get a single user as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     When I request "GET" a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get list of users then get a single user as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     When I request "GET" a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get non existing user
#     Given a user with role "Admin"
#     When I request "GET" "/users/9999"
#     Then the response status code should be 404

#   #Test Patch /users
#   #something wrong with payload but what???
#   Scenario: Update existing user as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     Given I set the "Content-Type" header to "application/merge-patch+json"
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Then I request "PATCH" a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Update existing user using wrong payload
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Then I request "PATCH" a single data from "userList"
#     Then the response status code should be 415

#   Scenario: Update existing user using wrong data
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     Given I set the "Content-Type" header to "application/merge-patch+json"
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Then I request "PATCH" a single data from "userList"
#     Then the response status code should be 400

#   Scenario: Update existing user as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     When I request "PATCH" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Update existing user as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     When I request "PATCH" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Update non existing user
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Given a user with role "Admin"
#     When I request "PATCH" "/users/99999"
#     Then the response status code should be 404

#   #Test PUT /users
#   Scenario: Replace existing user as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Then I request "PUT" a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Replace existing user using wrong data
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I set payload
#       """
#       """
#     Then I request "PUT" a single data from "userList"
#     Then the response status code should be 400

#   Scenario: Replace existing user as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     When I request "PUT" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Replace existing user as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     When I request "PUT" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Replace non existing user
#     When I set payload
#       """
#       {
#         "username": "test1",
#         "password": "test",
#         "email": "test1@gmail.com"
#       }
#       """
#     Given a user with role "Admin"
#     When I request "PUT" "/users/99999"
#     Then the response status code should be 404

#   #Test DELETE /users
#   Scenario: Delete existing user as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     Then I request "DELETE" a single data from "userList"
#     Then the response status code should be 204

#   Scenario: Delete existing user as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     Then I request "DELETE" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Delete existing user as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     Then I request "DELETE" a single data from "userList"
#     Then the response status code should be 403

#   Scenario: Delete non existing user
#     Given a user with role "Admin"
#     When I request "DELETE" "/users/99999"
#     Then the response status code should be 404

#   #Test GET /users/{id}/products
#   Scenario: Get user products as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I request "GET" "products" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user products as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     When I request "GET" "products" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user products as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     When I request "GET" "products" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user products from non existing user
#     Given a user with role "Admin"
#     When I request "DELETE" "/users/99999/products"
#     Then the response status code should be 405

#   #Test GET /users/{id}/orders
#   Scenario: Get user orders as admin
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Admin"
#     When I request "GET" "orders" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user orders as user
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "User"
#     When I request "GET" "orders" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user orders as seller
#     When I request "GET" "/users"
#     Then the response status code should be 200
#     And The "content-type" header response should exist
#     And The "content-type" header response should be "application/ld+json; charset=utf-8"
#     Then I store the result in "userList"
#     Given a user with role "Seller"
#     When I request "GET" "orders" from a single data from "userList"
#     Then the response status code should be 200

#   Scenario: Get user orders from non existing user
#     Given a user with role "Admin"
#     When I request "DELETE" "/users/99999/orders"
#     Then the response status code should be 405
