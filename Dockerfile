# -------------------------------
# Stage 1: Build dependencies
# -------------------------------
FROM composer:2 AS build
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# -------------------------------
# Stage 2: Runtime
# -------------------------------
FROM php:8.2-cli

# 🧩 Cài extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd

WORKDIR /app
COPY --from=build /app /app

# 🧩 Copy file .env.example thành .env
RUN cp .env.example .env || true

# 🧩 Phân quyền cho storage và cache
RUN chmod -R 775 storage bootstrap/cache || true

# 🧩 Tạo APP_KEY, clear & cache config
RUN php artisan key:generate --force || true
RUN php artisan config:clear && php artisan route:clear && php artisan cache:clear

# 🧩 Render tự cấp PORT qua biến môi trường
ENV PORT=10000
EXPOSE 10000

# 🧩 Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=$PORT
