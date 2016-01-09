FROM php:5.6

# install pdo_sqlite extension
RUN apt-get update && apt-get install --yes libsqlite3-dev
RUN docker-php-ext-install pdo pdo_sqlite

# install mbstring extension (required by beberlei/assert)
RUN docker-php-ext-install mbstring

# set timezone to UTC
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/date.timezone.ini

# install composer
RUN apt-get install --yes wget
RUN wget http://getcomposer.org/composer.phar -O /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /app

CMD ["/app/run.sh"]
