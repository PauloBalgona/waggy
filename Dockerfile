FROM php:8.3-apache

# -------------------------------------------------
# 1. System packages + Node
# -------------------------------------------------
RUN apt-get update && apt-get install -y \
    git zip unzip curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev \
    tesseract-ocr tesseract-ocr-eng \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------
# 2. Apache config
# -------------------------------------------------
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# -------------------------------------------------
# 3. Workdir
# -------------------------------------------------
WORKDIR /var/www/html

# -------------------------------------------------
# 4. Copy app
# -------------------------------------------------
COPY . .

# -------------------------------------------------
# 5. Composer (NO scripts)
# -------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# -------------------------------------------------
# 6. BUILD VITE (THIS FIXES 500 ERROR)
# -------------------------------------------------
RUN npm install && npm run build

# -------------------------------------------------
# 7. Permissions
# -------------------------------------------------
RUN mkdir -p storage/framework/{cache,sessions,views} && \
    chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# -------------------------------------------------
# 8. Runtime
# -------------------------------------------------
CMD php artisan storage:link || true && apache2-foreground
