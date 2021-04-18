## Very Simple Expenses API

This is a very basic expenses API, as a proof of concept using Symfony.

The PHP code consists of a couple of controller classes, a repository, a service class and an entity, along with some unit tests, generated code such as the database migrations, and files created on setting up a Symfony project.

I have used MySQL as the database, and the generated migrations code requires it, but the rest of the code is database agnostic thanks to Doctrine, and to the fields not using any database-specific features.

In addition to the unit tests, there is also a HTML/Javascript test harness to run manual tests on the API. This provides forms that trigger actions on the various API endpoints. Although the requirements said a front-end was not needed, I've used this to sanity-check that the API does actually work in a real-world environment.


# To install

* Clone the repo.
* `cd <project_base>`
* `composer install`
* `symfony serve`
* Browse to `localhost:8000/test.html` to see the manual test harness page.


# Assumptions

* I have assumed that a valid description for an expense must be a string of at least one character.
* I have assumed that a valid value for an expense must be a positive integer. (ie the value is in pence, not pounds with a decimal)


# OpenAPI

I have not provided an OpenAPI schema for this project. I am aware of Swagger / OpenAPI but have not used it in a PHP context, and did not have time to familiarise myself with the required libraries.


# Author

Simon Champion, April 18th 2021.

