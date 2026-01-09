# =================================================
# BASE IMAGE
# =================================================
FROM php:8.3-apache

# =================================================
# SYSTEM DEPENDENCIES
# =================================================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    tesseract-ocr \
    tesseract-ocr-eng \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# =================================================
# ENABLE APACHE REWRITE
# =================================================
RUN a2enmod rewrite

# =================================================
# APACHE → LARAVEL PUBLIC
# =================================================
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# =================================================
# INSTALL NODEJS (VITE)
# =================================================
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# =================================================
# INSTALL COMPOSER
# =================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =================================================
# WORKDIR
# =================================================
WORKDIR /var/www/html

# =================================================
# COPY APP FILES
# =================================================
COPY . .

# =================================================
# PERMISSIONS (IMPORTANT BEFORE ARTISAN)
# =================================================
RUN mkdir -p storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# =================================================
# COMPOSER INSTALL
# =================================================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# =================================================
# VITE BUILD
# =================================================
RUN npm install && npm run build

# =================================================
# EXPOSE
# =================================================
EXPOSE 80

# =================================================
# RUNTIME COMMANDS
# =================================================
CMD php artisan key:generate --force && \
    php artisan storage:link && \
    apache2-foreground
