#!/bin/sh

set -e

vendor/bin/phpspec run
vendor/bin/phpunit

exit 0