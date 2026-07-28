#!/bin/sh
set -e

# Run migrations & seeders on container startup in production
php artisan migrate --force
php artisan db:seed --force

# Start Laravel web server
exec php artisan serve --host=0.0.0.0 --port=80
