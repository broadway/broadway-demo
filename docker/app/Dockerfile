FROM php:7

# install pdo_sqlite extension
RUN apt-get update && apt-get install --yes libsqlite3-dev git zlib1g-dev
RUN docker-php-ext-install pdo pdo_sqlite zip mbstring

# set timezone to UTC
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/date.timezone.ini

# install composer
RUN apt-get install --yes wget
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN chmod +x /usr/local/bin/composer

WORKDIR /app

CMD ["/app/run.sh"]
