FROM node:lts-slim AS npm-stage

WORKDIR /var/www/html
COPY ./package*.json ./
RUN npm install
COPY . .
RUN npm run build


FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    vim

RUN a2enmod rewrite

RUN docker-php-ext-install pdo_mysql zip

WORKDIR /var/www/html

COPY . .

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=npm-stage --chown=www-data:www-data /var/www/html /var/www/html

RUN composer install


RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

