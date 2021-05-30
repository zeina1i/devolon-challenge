FROM php:7.2-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version


WORKDIR /devolon
COPY ./ /devolon

ARG USER=application
ARG GROUP=application
RUN addgroup -g 1000 -S ${GROUP} && \
        adduser -u 1000 -S ${USER} -G ${GROUP}

RUN  composer install --no-dev --no-scripts --optimize-autoloader

RUN chown -hR application:application /devolon
