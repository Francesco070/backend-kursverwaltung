FROM php:8.0-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|Directory /var/www/html|Directory /var/www/html/public|' /etc/apache2/apache2.conf \
    && a2enmod rewrite

COPY . /var/www/html/
