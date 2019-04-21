#!/usr/bin/env bash
set -ex

docker-compose -f docker-compose-test.yml run tester docker/watch.sh