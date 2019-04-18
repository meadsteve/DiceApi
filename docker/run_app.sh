#!/usr/bin/env sh
set -ex

# The template conf file needs the PORT variable put into it
envsubst '\$PORT' < /etc/nginx/conf.d/nginx-site.template > /etc/nginx/conf.d/nginx-site.conf

nginx
php-fpm