FROM php:8.3-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        libpng-dev \
        libjpeg-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        libzip-dev \
        libpq-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd bcmath opcache zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
