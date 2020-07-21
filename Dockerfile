FROM php:7.4-fpm as base_image

WORKDIR /var/www/html/laravel

# Installing dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libonig-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configuration for supporting cyrillic fonts in bash
RUN echo "set meta-flag on" >> /etc/.inputrc \
    echo "set convert-meta off" >> /etc/.inputrc

# Assets
FROM node:latest as assets

WORKDIR /var/www/html/laravel

COPY ./package.json     /var/www/html/laravel/package.json
COPY ./webpack.mix.js   /var/www/html/laravel/webpack.mix.js
COPY ./resources        /var/www/html/laravel/resources

RUN yarn && yarn dev

FROM base_image as dev

COPY  ./.container/php/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
COPY --chown=www-data --from=assets /var/www/html/laravel /var/www/html/laravel

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html/laravel
