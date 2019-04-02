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

# Copy over our app code
COPY  ./www /app/www/
COPY  ./src /app/src/

# Now all the app code is over we can build the final autoloader and
# remove composer.
RUN php composer.phar dump-autoload --no-dev \
 && rm composer.phar

# This is the final image that we'll serve from
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
COPY ./docker/run_app.sh /etc/run_app.sh

# The builder has already pulled all composer deps & built the autoloader
COPY --from=builder /app /app

EXPOSE 80 443
CMD ["/etc/run_app.sh"]
HEALTHCHECK CMD curl http://localhost:80/health-check