FROM php:8.4-cli

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader

# Run app (Railway will override this if needed)
CMD php artisan serve --host=0.0.0.0 --port=8080