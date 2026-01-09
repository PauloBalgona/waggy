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
    curl \
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
# 4. Install Node.js (FOR VITE)
# -------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# -------------------------------------------------
# 5. Workdir
# -------------------------------------------------
WORKDIR /var/www/html

# -------------------------------------------------
# 6. Copy app
# -------------------------------------------------
COPY . .

# -------------------------------------------------
# 7. Prepare Laravel cache directories (FIX)
# -------------------------------------------------
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# -------------------------------------------------
# 8. Composer (NOW SAFE)
# -------------------------------------------------
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# -------------------------------------------------
# 9. Vite build
# -------------------------------------------------
RUN npm install && npm run build
