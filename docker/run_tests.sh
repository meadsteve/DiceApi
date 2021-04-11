#!/usr/bin/env sh
set -ex

tests="
  ./vendor/bin/phpunit -c tests/app-tests.xml
  ./vendor/bin/phpspec run
  ./vendor/bin/psalm --show-info=false
  ./vendor/bin/phpcs --standard=PSR2 --ignore=src/Protos src/
"

ash -c "$tests"