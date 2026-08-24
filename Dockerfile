FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    tzdata

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    zip

RUN a2enmod rewrite

ENV TZ=America/Mazatlan

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

RUN echo "date.timezone=America/Mazatlan" > \
    /usr/local/etc/php/conf.d/timezone.ini

# ========================================
# XDEBUG
# ========================================

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

# ========================================
# HERRAMIENTAS UTILES
# ========================================

RUN apt-get update && apt-get install -y \
    git \
    nano \
    vim \
    curl

# ========================================
# COMPOSER
# ========================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html