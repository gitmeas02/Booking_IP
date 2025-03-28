FROM php:8.2-fpm

# Install system dependencies
RUN apt update && apt install -y unzip curl git nodejs npm libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer install
# Set working directory
WORKDIR /var/www

