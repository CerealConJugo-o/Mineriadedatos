FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    python3 \
    python3-pip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        pdo_pgsql \
        pgsql \
        zip \
        mbstring \
        xml \
        intl \
        gd \
        bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

RUN pip3 install --break-system-packages \
    pandas \
    numpy \
    scikit-learn \
    mysql-connector-python
	psycopg2-binary

RUN a2enmod rewrite

RUN mkdir -p /var/www/html/storage/logs
RUN mkdir -p /var/www/html/bootstrap/cache

RUN touch /var/www/html/storage/logs/laravel.log

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true
RUN php artisan cache:clear || true

RUN mkdir -p /var/www/html/public/datasets
RUN chown -R www-data:www-data /var/www/html/public/datasets
RUN chmod -R 775 /var/www/html/public/datasets

EXPOSE 80

CMD ["apache2-foreground"]