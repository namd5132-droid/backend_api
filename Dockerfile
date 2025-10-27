# # ----- Stage 1: Build -----
# FROM composer:2 AS build
# WORKDIR /app

# # Copy toàn bộ mã nguồn vào container
# COPY . .

# # Cài đặt dependencies của Laravel
# RUN composer install --no-dev --optimize-autoloader

# # ----- Stage 2: Run -----
# FROM php:8.2-cli

# # Cài đặt extension cần thiết cho Laravel (cho MySQL)
# RUN docker-php-ext-install pdo pdo_mysql

# # Sao chép ứng dụng đã build
# WORKDIR /app
# COPY --from=build /app /app

# # Tạo APP_KEY
# RUN php artisan key:generate --force || true

# # Render tự cấp PORT qua biến môi trường
# ENV PORT=10000

# # Chạy server Laravel
# CMD php artisan serve --host=0.0.0.0 --port=$PORT
# -------------------------------
# Stage 1: Build dependencies
# -------------------------------
FROM composer:2 AS build
WORKDIR /app

# Copy toàn bộ code vào container
COPY . .

# Cài đặt dependency cho Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# -------------------------------
# Stage 2: Runtime
# -------------------------------
FROM php:8.2-cli

# Cài đặt extension cần thiết cho Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copy code từ stage build
WORKDIR /app
COPY --from=build /app /app

# Thiết lập quyền cho storage và bootstrap
RUN chmod -R 775 storage bootstrap/cache || true

# Sinh APP_KEY nếu chưa có (Render chạy lần đầu)
RUN php artisan key:generate --force || true

# Clear và cache lại cấu hình Laravel
RUN php artisan config:clear && php artisan config:cache && php artisan route:cache

# Render sẽ truyền PORT động qua biến môi trường
ENV PORT=10000

# Expose port cho Laravel
EXPOSE 10000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=$PORT
