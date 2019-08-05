# Article parser

[![Build Status](https://travis-ci.org/lenivezzz/article-parser.svg?branch=master)](https://travis-ci.org/lenivezzz/article-parser)


### Available to parse:
- rbc.ru


### How to start:

`composer install --no-interaction`

`cp .env.sample .env`

`cp phpunit.sample.xml phpunit.xml`

Create database

Fill settings in file .env

`php cli/migrate.php`

`php cli/import.php`

`php -S localhost:8000 -t public/`
