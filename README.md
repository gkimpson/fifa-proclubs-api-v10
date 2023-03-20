## FIFA ProClubs API for FIFA 23 (Laravel 10.x) - Work in Progress

FIFA Pro Clubs API, built on PHP/MySQL with Laravel 10.x, this retrieve game stats for all platforms of the FIFA 23 video game.

Note - there is currently no real 'frontend' as of yet, this will be coming it is currently just API requests that return json data based on a users clubId & platform (e.g PC, PlayStation 5 etc...)

## Requirements
PHP 8.1 +
MySQL

## Setup
- clone the repository
- `composer install` (for dependencies)
- `npm install` (frontend) & `npm run dev` (to build js/css assets)
- `cp .env.example .env` 
- modify the .env with your MySQL DB host, user & password details
- run `php artisan config:clear` to reload the modified .env
- run `php artisan migrate` to create the database tables
- run `php artisan db:seed`
- run `php artisan proclubs:matches` to retrieve the last 5 matches for the user in the database
- run `php artisan test` to run all the tests
- there is a single user in the users table, the password is 'password' (although you don't need to login)
- postman collection can be imported into Postman app, in the _misc directory
