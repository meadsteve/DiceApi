#!/usr/bin/env ash
set -ex

# Rerun the tests when any php file changes
find . -name '*.php' | entr ./docker/run_tests.sh
