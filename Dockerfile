FROM php:8.2-cli

# Install system deps & PHP extensions (pdo_pgsql is REQUIRED for Supabase)
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pdo_mysql mbstring xml curl zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy app files
COPY . .

# Install production dependencies only
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Fix Laravel storage/cache permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache

# Render injects $PORT at runtime. This binds to it.
EXPOSE 8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]