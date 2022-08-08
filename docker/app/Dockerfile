FROM php:8.1-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update -y && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    libicu-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ADD . /app
WORKDIR /app
CMD bash -c "composer install \
&& sleep 5 \
&& php bin/console doctrine:database:create \
&& sleep 4 \
&& php bin/console doctrine:migrations:migrate --no-interaction"