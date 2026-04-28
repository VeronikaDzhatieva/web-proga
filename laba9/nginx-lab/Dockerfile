FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/html/tests/logs && chmod 777 /var/www/html/tests/logs

WORKDIR /var/www/html

COPY ./www /var/www/html
COPY ./tests /var/www/html/tests

COPY phpunit.xml /var/www/html/phpunit.xml 2>/dev/null || true

CMD ["php-fpm"]