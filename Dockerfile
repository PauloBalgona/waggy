# ================================
# BASE IMAGE
# ================================
FROM php:8.3-apache

# ================================
# SYSTEM DEPENDENCIES
# ================================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    tesseract-ocr \
    tesseract-ocr-eng \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        zip \
        gd \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ================================
# APACHE
# ================================
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# ================================
# INSTALL NODE (VITE)
# ================================
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# ================================
# INSTALL COMPOSER
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ================================
# WORKDIR
# ================================
WORKDIR /var/www/html

# ================================
# COPY FILES
# ================================
COPY . .

# ================================
# FIX LARAVEL CACHE PATH
# ================================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
 && touch \
    bootstrap/cache/.gitignore \
    storage/framework/cache/.gitignore \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache


# ================================
# INSTALL PHP DEPS
# ================================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ================================
# BUILD FRONTEND (VITE)
# ================================
RUN npm install && npm run build

# ================================
# STORAGE LINK (BUILD TIME)
# ================================
RUN php artisan storage:link

# ================================
# EXPOSE
# ================================
EXPOSE 80

# ================================
# RUNTIME (APACHE ONLY)
# ================================
CMD ["apache2-foreground"]
