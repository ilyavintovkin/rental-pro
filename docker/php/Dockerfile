FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    autoconf \
    g++ \
    make \
    nodejs \
    npm \
    supervisor \
    linux-headers

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl \
    && docker-php-ext-enable opcache

# Устанавливаем Redis расширение
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# 🔧 Устанавливаем системные библиотеки для сборки sqlite3 и Node.js
RUN apk add --no-cache --update sqlite-libs sqlite-dev python3 make g++ nodejs npm

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаем пользователя
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

USER www

WORKDIR /var/www

CMD ["php-fpm"]