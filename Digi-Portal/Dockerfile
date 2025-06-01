# --- Build Stage (Install PHP and Dependencies) ---
    FROM php:8.3-apache AS web

    # Install required system packages
    RUN apt-get update && apt-get install -y \
        zip unzip curl git libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
        libonig-dev libicu-dev \
        && apt-get clean && rm -rf /var/lib/apt/lists/*
    
    # Enable Apache mod_rewrite
    RUN a2enmod rewrite
    
    # Install PHP extensions
    RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install pdo_mysql zip gd mbstring intl
    
    # Set working directory
    WORKDIR /var/www/html
    
    # Set Apache public directory
    ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
    RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
    
    # Install Composer globally
    RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin --filename=composer
    
    # Copy entire app first
    COPY . .
    
    # Install dependencies after app is in place
    RUN composer install --no-dev --prefer-dist --optimize-autoloader
    
    # Set permissions
    RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    
    # Expose port 80
    EXPOSE 80
    
    # Copy and use custom entrypoint for runtime commands
    COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
    RUN chmod +x /usr/local/bin/docker-entrypoint.sh
    
    ENTRYPOINT ["docker-entrypoint.sh"]
    
    # Run Apache in the foreground
    CMD ["apache2-foreground"]
    