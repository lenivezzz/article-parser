#Article parser

Available to parse:
- rbc.ru


How to install:

- `composer install --no-interaction`
- `cp .env.sample .env`
- `cp phpunit.sample.xml phpunit.xml`
- Create database
- Fill settings in file .env
- `php cli/migrate.php`
- `php cli/import.php`
- `php -S localhost:8000 -t public/`
