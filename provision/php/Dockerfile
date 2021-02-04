FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        zip \
        unzip\
    && docker-php-ext-install \
        pdo_mysql \
        zip

WORKDIR /application


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY \
    ./provision/php/script/ \
    /usr/local/bin/


CMD ["run.sh"]

EXPOSE 9000
