#!/bin/sh

composer install
wait-for-it.sh -t 120 percona:3306 -- php ./cli/migrate.php
php-fpm
