#!/usr/bin/env sh
set -ex

nginx -g 'pid /tmp/nginx.pid;'
php-fpm