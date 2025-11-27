#!/bin/bash

# Script to clear Laravel caches in the Docker container

echo "Clearing Laravel caches..."

docker exec app_hotel php artisan config:clear
docker exec app_hotel php artisan cache:clear
docker exec app_hotel php artisan view:clear
docker exec app_hotel php artisan route:clear

echo "All caches cleared successfully."