FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libssl-dev pkg-config unzip git ca-certificates \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start-apache.sh /usr/local/bin/start-apache
RUN chmod +x /usr/local/bin/start-apache

EXPOSE 10000
CMD ["/usr/local/bin/start-apache"]
