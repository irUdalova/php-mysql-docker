FROM php:apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli