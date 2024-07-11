FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Ensure the SQLite database file and its directory have the correct permissions
RUN chown -R www-data:www-data /var/www/html/templates
RUN chmod -R 777 /var/www/html/templates/databasepcos.db

# Expose port 80
EXPOSE 80
