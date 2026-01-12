FROM php:8.3-fpm AS base

WORKDIR /app

# Install system dependencies and clean up in same layer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd xml dom \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================
# Development target
# ============================================
FROM base AS dev

# Accept host user UID/GID to fix permission issues with mounted volumes
ARG UID=1000
ARG GID=1000

# Modify www-data to match host user UID/GID
RUN usermod -u ${UID} www-data \
    && groupmod -g ${GID} www-data \
    && mkdir -p /app/storage/logs /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views /app/bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]

# ============================================
# Production target
# ============================================
FROM base AS prod

RUN mkdir -p /app/storage/logs /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views /app/bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

COPY --chown=www-data:www-data . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
