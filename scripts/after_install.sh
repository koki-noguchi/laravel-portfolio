#!/bin/bash

set -eux

cd /var/www/laravel-portfolio
php artisan migrate --force
php artisan config:cache