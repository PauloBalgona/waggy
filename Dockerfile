FROM php:8.3-apache

# -------------------------------------------------
# 1. System packages
# -------------------------------------------------
RUN apt-get update && apt-get install -y \
    tesseract-ocr \
    tesseract-ocr-eng \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------
# 2. Enable Apache rewrite
# -------------------------------------------------
RUN a2enmod rewrite

# -------------------------------------------------
# 3. Apache → Laravel public
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
# 5. Copy app
# -------------------------------------------------
COPY . .

# -------------------------------------------------
# 6. Composer
# -------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# -------------------------------------------------
# 7. Permissions
# -------------------------------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# -------------------------------------------------
# 8. RUNTIME ONLY (SAFE)
# -------------------------------------------------
CMD php artisan key:generate --force || true && \
    php artisan migrate --force || true && \
    php artisan storage:link || true && \
    apache2-foreground
