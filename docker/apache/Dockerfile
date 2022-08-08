FROM php:8.1-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
COPY docker/apache/apache.conf /etc/apache2/sites-enabled/000-default.conf
RUN apt-get update -y && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    libicu-dev \
    apache2 \
    apache2-utils 
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
CMD bash -c "composer install \
&& sleep 5 \
&& php bin/console doctrine:database:create \
&& sleep 4 \
&& php bin/console doctrine:migrations:migrate --no-interaction \
&& /usr/sbin/apache2ctl -D FOREGROUND"
