FROM php:8.3-apache

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions mongodb

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends unzip git; \
    a2enmod rewrite; \
    sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf; \
    sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY . .

RUN set -eux; \
    if [ -d Views/Students ] && [ ! -d Views/students ]; then mv Views/Students Views/students; fi

EXPOSE 10000

CMD sed -ri -e "s!Listen 80!Listen ${PORT:-10000}!g" /etc/apache2/ports.conf \
    && sed -ri -e "s!<VirtualHost \*:80>!<VirtualHost *:${PORT:-10000}>!g" /etc/apache2/sites-available/*.conf \
    && apache2-foreground
