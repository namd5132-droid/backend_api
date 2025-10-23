# ----- Stage 1: Build -----
FROM composer:2 AS build
WORKDIR /app

# Copy toàn bộ mã nguồn vào container
COPY . .

# Cài đặt dependencies của Laravel
RUN composer install --no-dev --optimize-autoloader

# ----- Stage 2: Run -----
FROM php:8.2-cli

# Cài đặt extension cần thiết cho Laravel (cho MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Sao chép ứng dụng đã build
WORKDIR /app
COPY --from=build /app /app

# Tạo APP_KEY
RUN php artisan key:generate --force || true

# Render tự cấp PORT qua biến môi trường
ENV PORT=10000

# Chạy server Laravel
CMD php artisan serve --host=0.0.0.0 --port=$PORT
