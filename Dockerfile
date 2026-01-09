FROM php:8.3-apache

# -------------------------------------------------
# 1. System packages + PHP extensions
# -------------------------------------------------
RUN apt-get update && apt-get install -y \
    git zip unzip \
    tesseract-ocr tesseract-ocr-eng \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------
# 2. Enable Apache rewrite
# -------------------------------------------------
RUN a2enmod rewrite

# -------------------------------------------------
# 3. Apache → Laravel public directory
# -------------------------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# -------------------------------------------------
# 4. Workdir
# -------------------------------------------------
WORKDIR /var/www/html

# -------------------------------------------------
# 5. Copy application files
# -------------------------------------------------
COPY . .

# -------------------------------------------------
# 6. Composer install (NO SCRIPTS – SAFE FOR RENDER)
# -------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# -------------------------------------------------
# 7. 🔥 CRITICAL: Create Laravel cache directories
# -------------------------------------------------
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# -------------------------------------------------
# 8. Permissions (FIXES HTTP 500)
# -------------------------------------------------
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# -------------------------------------------------
# 9. Clear caches (SAFE)
# -------------------------------------------------
RUN php artisan config:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true

# -------------------------------------------------
# 10. Expose port
# -------------------------------------------------
EXPOSE 80

# -------------------------------------------------
# 11. Runtime commands (Render-safe)
# -------------------------------------------------
CMD php artisan storage:link || true && \
    apache2-foreground
