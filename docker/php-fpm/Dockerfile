FROM php:7.2-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version


WORKDIR /devolon
COPY ./ /devolon

RUN  composer install --no-dev --no-scripts --optimize-autoloader

RUN chown -hR www-data:www-data /devolon
