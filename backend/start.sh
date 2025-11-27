#!/bin/bash

# Clear config cache on startup to ensure .env changes are picked up
cd /var/www
php artisan config:clear || true

# Start PHP-FPM
php-fpm