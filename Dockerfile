FROM php:8.3-apache

# Install System Dependencies & PDO MySQL extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring gd

# Enable Apache Mod_Rewrite
RUN a2enmod rewrite

# Set Document Root to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy Application Files
WORKDIR /var/www/html
COPY . /var/www/html/

# Set File Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

EXPOSE 80
CMD ["apache2-foreground"]
