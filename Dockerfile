# GuardOps — multi-stage PHP 8.3-FPM image
# Stages: composer deps → frontend assets → runtime

# -----------------------------------------------------------------------------
# Stage 1: PHP dependencies
# -----------------------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize --no-dev --no-scripts

# -----------------------------------------------------------------------------
# Stage 2: Frontend build
# -----------------------------------------------------------------------------
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts || npm install

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN npm run build

# -----------------------------------------------------------------------------
# Stage 3: Runtime (PHP 8.3-FPM)
# -----------------------------------------------------------------------------
FROM php:8.3-fpm-bookworm AS app

LABEL org.opencontainers.image.title="GuardOps" \
      org.opencontainers.image.description="Security Guard and Patrol Management SaaS"

ARG WWWUSER=1000
ARG WWWGROUP=1000

ENV DEBIAN_FRONTEND=noninteractive \
    APP_HOME=/var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        intl \
        zip \
        bcmath \
        sockets \
        pcntl \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

# Non-root application user
RUN groupadd --gid ${WWWGROUP} guardops \
    && useradd --uid ${WWWUSER} --gid guardops --shell /bin/bash --create-home guardops

WORKDIR ${APP_HOME}

COPY --from=vendor --chown=guardops:guardops /app/vendor ./vendor
COPY --chown=guardops:guardops . .
COPY --from=frontend --chown=guardops:guardops /app/public/build ./public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R guardops:guardops storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

USER guardops

EXPOSE 9000

CMD ["php-fpm"]
