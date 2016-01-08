#!/bin/bash

set -ev

phpenv local 5.6

composer self-update

if [[ '1' == $COVERAGE ]]; then
	composer install
else
	composer install --no-dev
fi

phpenv local --unset

phpenv rehash

wget https://raw.githubusercontent.com/wp-cli/sample-plugin/master/bin/install-wp-tests.sh

bash install-wp-tests.sh wordpress_test root '' localhost $WP_VERSION 
