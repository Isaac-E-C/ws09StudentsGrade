FROM php:8.3-apache

WORKDIR /var/www/html

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        libssl-dev \
        pkg-config \
        unzip \
        git; \
    pecl install mongodb; \
    docker-php-ext-enable mongodb; \
    a2enmod rewrite; \
    sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf; \
    sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $PHPIZE_DEPS; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY . .

EXPOSE 10000

CMD sed -ri -e "s!Listen 80!Listen ${PORT:-10000}!g" /etc/apache2/ports.conf \
    && sed -ri -e "s!<VirtualHost \*:80>!<VirtualHost *:${PORT:-10000}>!g" /etc/apache2/sites-available/*.conf \
    && apache2-foreground
