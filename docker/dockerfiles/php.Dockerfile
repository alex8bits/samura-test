FROM php:8.3-fpm

WORKDIR /var/www/laravel

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    unzip \
    zip \
    git \
    libzip-dev \
    libpq-dev \
    curl \
    && apt-get clean

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring bcmath zip

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN chown -R www-data:www-data /var/www/laravel
USER www-data
