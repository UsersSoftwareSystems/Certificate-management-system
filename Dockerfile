FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nano \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Fix user ID mapping to match host system (WSL2)
# This resolves the "File is unwritable" permission issues
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Create Laravel directories with proper structure
RUN mkdir -p storage/logs \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    database/migrations \
    database/seeders \
    app/Models \
    app/Http/Controllers \
    app/Http/Requests \
    app/Services \
    resources/views \
    resources/js \
    resources/css \
    config \
    routes \
    public

# Copy application files
COPY . /var/www/html

# Set proper ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/app \
    && chmod -R 755 /var/www/html/database \
    && chmod -R 755 /var/www/html/resources \
    && chmod -R 755 /var/www/html/config \
    && chmod -R 755 /var/www/html/routes

# Switch to www-data user for file operations
USER www-data

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
