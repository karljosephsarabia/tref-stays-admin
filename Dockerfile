FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    ffmpeg \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Force cache invalidation - updated 2026-02-08
ENV NPM_BUILD_SKIPPED=true

# Create storage link
RUN php artisan storage:link || true

# Set permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/public

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache document root to Laravel's public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Expose port (Render will provide the PORT env variable)
EXPOSE 80

# Create startup script to run migrations
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Running Laravel setup..."\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan migrate --force || echo "Migrations failed or already run"\n\
echo "Starting Apache..."\n\
exec apache2-foreground' > /usr/local/bin/start.sh && \
chmod +x /usr/local/bin/start.sh

# Start Apache with migrations
CMD ["/usr/local/bin/start.sh"]
