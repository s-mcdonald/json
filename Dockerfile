# Use the official PHP 8.3 CLI image as the base
FROM php:8.3-cli

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHPUnit globally
RUN composer global require phpunit/phpunit --prefer-dist

# Add Composer's global bin directory to PATH
ENV PATH="$PATH:/root/.composer/vendor/bin"

# Set working directory for your library code
WORKDIR /usr/src/app

# Copy your project's code into the container
COPY . /usr/src/app

# Ensure Composer installs dependencies
RUN if [ -f "composer.json" ]; then composer install; fi

# Expose port 9003 for Xdebug
EXPOSE 9003

# Default command to keep the container running (useful for remote interpreter & debugging)
CMD ["php", "-a"]
