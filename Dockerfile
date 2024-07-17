# Use an official PHP runtime as a base image
FROM php:7.3-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Update package lists and install necessary packages
RUN apt-get update \
    && apt-get install -y \
        build-essential \
        libxml2-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pdo_mysql xml zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files to the container
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port 9000 to be used by php-fpm
EXPOSE 9000

# Run PHP-FPM server
CMD ["php-fpm"]
