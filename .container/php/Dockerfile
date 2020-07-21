FROM php:7.4-fpm

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

COPY "memory-limit.ini" "/usr/local/etc/php/conf.d/memory-limit.ini"

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html/laravel
