FROM php:5.6.30-apache
ADD ./conf/php.ini /usr/local/etc/php
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng12-dev sendmail
RUN a2enmod rewrite
RUN docker-php-ext-install -j$(nproc) mysqli && pecl install xdebug-2.5.0 && docker-php-ext-enable xdebug
RUN docker-php-ext-install -j$(nproc) iconv mcrypt
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
EXPOSE 80