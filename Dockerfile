FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Set environment variable for composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install --no-interaction
RUN npm install
RUN npm run build

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=$PORT
