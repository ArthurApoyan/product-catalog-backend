# Use an official PHP 8.2 runtime
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Laravel dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-scripts --no-autoloader --optimize-autoloader

# Copy default .env and set APP_KEY
COPY .env.example .env
RUN php artisan key:generate

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 8000

# Run Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
