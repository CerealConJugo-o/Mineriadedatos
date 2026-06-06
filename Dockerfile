FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    python3 \
    python3-pip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

RUN pip3 install --break-system-packages \
    pandas \
    numpy \
    scikit-learn \
    mysql-connector-python

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

RUN mkdir -p storage/logs bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true
RUN php artisan cache:clear || true

RUN touch storage/logs/laravel.log
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
CMD ["apache2-foreground"]