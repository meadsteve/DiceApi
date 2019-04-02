FROM php:7.3-fpm AS base

RUN apt-get update -y \
    && apt-get install -y nginx

# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

RUN docker-php-ext-install opcache

# Has all the files AND composer installed constructs everything needed for the app
FROM base AS builder
WORKDIR /app

# Composer needs git and some zip deps
RUN apt-get install -y git libzip-dev
RUN docker-php-ext-install zip

COPY ./docker/install_composer.sh /tmp/install_composer.sh
RUN /tmp/install_composer.sh && rm /tmp/install_composer.sh

# Install the dependencies
COPY ./composer.* /app/
RUN php composer.phar install \
 && rm -rf /home/root/.composer/cache

# Copy over out app code
COPY  ./www /app/www/
COPY  ./src /app/src/

RUN php composer.phar dump-autoload --no-dev

FROM base AS final
WORKDIR /app
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/php-opocache-cfg.ini


COPY ./docker/nginx-site.conf /etc/nginx/sites-enabled/default
COPY ./docker/entrypoint.sh /etc/entrypoint.sh

COPY --from=builder /app /app

EXPOSE 80 443
ENTRYPOINT ["/etc/entrypoint.sh"]