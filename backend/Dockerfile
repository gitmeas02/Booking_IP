# Backend Dockerfile
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    supervisor \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY laravel/ .

# Set proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Install PHP dependencies - remove problematic commands
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy configuration files
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Create startup script without Laravel commands
RUN echo '#!/bin/sh' > /usr/local/bin/start.sh \
    && echo 'php-fpm' >> /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["/usr/local/bin/start.sh"]

