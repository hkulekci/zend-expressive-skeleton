FROM php:7.2-fpm

MAINTAINER Haydar KULEKCI <haydarkulekci@gmail.com>

RUN apt-get update && apt-get install -y \
    libpq-dev \
    curl \
    libssl-dev \
    libmcrypt-dev \
    --no-install-recommends \
    && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install mbstring

# Install extensions using the helper script provided by the base image
RUN docker-php-ext-install pdo_mysql

# Install apc
RUN pecl install apcu \
    && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini

WORKDIR /var/www/html

CMD ["php-fpm"]

EXPOSE 9000

