# ====== Build Stage ======
FROM composer:2.6 AS build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY . .

# ====== Run Stage ======
FROM php:8.2-fpm

# Cài extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Cài Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY --from=build /app ./

# Phân quyền cho Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8000 (Render mặc định dùng PORT env)
EXPOSE 8000

# Start Laravel app
CMD php artisan serve --host=0.0.0.0 --port=$PORT
