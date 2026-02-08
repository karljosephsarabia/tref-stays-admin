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
    npm \
    python3 \
    python3-pip \
    make \
    g++

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Create python symlink for node-gyp
RUN ln -sf /usr/bin/python3 /usr/bin/python

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

# Set Python path for node-gyp
ENV PYTHON=/usr/bin/python3

# Install Node dependencies and build assets
RUN npm install && npm run production

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

# Start Apache
CMD ["apache2-foreground"]
