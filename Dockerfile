# -------------------------------
# Stage 1: Build dependencies
# -------------------------------
FROM composer:2 AS build
WORKDIR /app

# Copy toàn bộ project vào container build
COPY . .

# Cài đặt dependency cho Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# -------------------------------
# Stage 2: Runtime
# -------------------------------
FROM php:8.2-cli

# Cài các extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd

# Copy code từ stage build
WORKDIR /app
COPY --from=build /app /app

# Copy .env.example thành .env (nếu chưa có)
RUN cp .env.example .env || true

# Cấp quyền ghi cho storage và bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache || true

# ⚠️ Không chạy lệnh artisan ở giai đoạn build (nó sẽ fail)
# Render sẽ generate APP_KEY khi container chạy

# Render cấp PORT tự động
ENV PORT=10000
EXPOSE 10000

# Khi container start → lúc này .env đã tồn tại → chạy artisan
CMD sh -c "php artisan key:generate --force || true && \
            php artisan config:clear && \
            php artisan route:clear && \
            php artisan cache:clear && \
            php artisan serve --host=0.0.0.0 --port=$PORT"
