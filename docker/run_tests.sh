#!/usr/bin/env sh
set -ex

./vendor/bin/phpunit -c tests/app-tests.xml
./vendor/bin/phpspec run
./vendor/bin/psalm --show-info=false
./vendor/bin/phpcs --standard=PSR2 --ignore=src/Protos src/
