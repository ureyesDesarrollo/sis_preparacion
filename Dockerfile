FROM php:7.4.33-apache-bullseye

ENV TZ=America/Mazatlan

# Bullseye ya está EOL.
# Usamos el repositorio archivado y eliminamos bullseye-security,
# que actualmente tiene paquetes faltantes/404.
RUN echo "deb http://archive.debian.org/debian bullseye main" > /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99archive

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        tzdata \
        chromium \
        git \
        curl \
        vim \
    && rm -rf /var/lib/apt/lists/*

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

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

RUN echo "date.timezone=$TZ" \
    > /usr/local/etc/php/conf.d/timezone.ini

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html