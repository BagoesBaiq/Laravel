#!/usr/bin/env bash
# Exit on error
set -o errexit

# Create storage symlink
php artisan storage:link

# Clear cache
php artisan optimize:clear

# Optimize
php artisan optimize

# Create empty database if using SQLite
touch database/database.sqlite
