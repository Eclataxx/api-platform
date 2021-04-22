# Feature: Address
#     Background:
#         Given the fixtures files
#             | address |
#             | cart    |
#             | user    |
#             | product |
#             | order   |

#     Scenario: Insert new address
#         Given a user with role "User"
#         When I request "GET" "/addresses"
#         Then the response status code should be 200
#         And The "content-type" header response should exist
#         And The "content-type" header response should be "application/ld+json; charset=utf-8"
