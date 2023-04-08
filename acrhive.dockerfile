FROM php:8.1.17-apache-bullseye as intermediate

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    zip \
    curl \
    unzip \
    sudo \
    libfreetype6-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libmagickwand-dev \
    libwebp-dev \
    g++ \
    mariadb-client \
    nano \
    subversion

RUN docker-php-ext-configure \
    gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN docker-php-ext-install -j "$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mysqli \
    bz2 \
    iconv \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip

RUN pecl install -f imagick && docker-php-ext-enable imagick

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite headers expires

