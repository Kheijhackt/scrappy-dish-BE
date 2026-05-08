FROM php:8.2-cli

# Added libcurl4-openssl-dev and pkg-config. This fixes the build error.
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libpng-dev libonig-dev libxml2-dev libzip-dev \
    libcurl4-openssl-dev pkg-config \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pdo_mysql mbstring xml curl zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Production install + optimization
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Fix permissions for Render's runtime user
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache

EXPOSE 8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]