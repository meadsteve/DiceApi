#!/usr/bin/env bash
set -ex

docker-compose -f docker-compose-test.yml run tester docker/run_tests.sh