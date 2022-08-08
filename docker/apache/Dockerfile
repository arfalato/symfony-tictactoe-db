FROM php:8.1-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
COPY docker/apache/apache.conf /etc/apache2/sites-enabled/000-default.conf

