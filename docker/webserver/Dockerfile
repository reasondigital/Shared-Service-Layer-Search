FROM php:7.4-apache

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Prepare to install packages
RUN apt update

## XDebug
RUN pecl install xdebug-2.9.8

## Vim
RUN apt install -y vim

## Un/Zip
RUN apt install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libfreetype6-dev
RUN apt install -y zlib1g-dev
RUN apt install -y libzip-dev
RUN docker-php-ext-install zip
RUN apt install -y unzip

## Node
RUN curl -sL https://deb.nodesource.com/setup_15.x | bash -
RUN apt install -y nodejs

# PHP extensions
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install tokenizer

# Executables
WORKDIR "/usr/local/bin"

## Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm /usr/local/bin/composer-setup.php
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer

# END Executables
WORKDIR "/var/www/html"

# Enable mod_rewrite
RUN a2enmod rewrite

# Restart Apache
RUN apachectl restart

# Make sure the server user owns the /var/www directory
RUN chown -R www-data:www-data /var/www
