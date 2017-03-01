Feature: Global behavior API "FoodMeUp"
    In order to check the global behavior API
    As a visitor
    I need to able to access API

    Scenario: Able to successfully access to Homepage API without basic auth
#        Given   No Given for scenario
        When    I send a GET request to "/"
        Then    the response status code should be 401

    Scenario: Able to successfully access to Homepage API with bad auth jwt token
        Given   I add "Authorization" header equal to "Bearer nogood"
        When    I send a GET request to "/"
        Then    the response status code should be 422

    Scenario: Able to successfully access to Homepage API with auth basic
        Given   I add "Authorization" header equal to "Basic cGFuZGE6dGVzdA=="
        When    I send a GET request to "/"
        Then    the response status code should be 200

    Scenario: Able to successfully access to Homepage API with auth jwt token
        Given   I add "Authorization" header equal to "Bearer fakejwttoken|dupond|ORIGAMIX-USER-JWT1-0000-000000000001|ROLE_USER"
        When    I send a GET request to "/"
        Then    the response status code should be 200