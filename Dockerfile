FROM php:7.2-fpm

WORKDIR /app

RUN apt-get update -y \
    && apt-get install -y nginx \
    && apt-get install -y git

# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

RUN docker-php-ext-install opcache
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/php-opocache-cfg.ini

COPY ./docker/install_composer.sh /tmp/install_composer.sh
RUN /tmp/install_composer.sh && rm /tmp/install_composer.sh

COPY ./composer.* /app/
RUN php composer.phar install \
    && rm -rf /home/root/.composer/cache

COPY ./docker/nginx-site.conf /etc/nginx/sites-enabled/default
COPY ./docker/entrypoint.sh /etc/entrypoint.sh

COPY  ./www /app/www/
COPY  ./src /app/src/


EXPOSE 80 443
ENTRYPOINT ["/etc/entrypoint.sh"]