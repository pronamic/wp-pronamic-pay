#!/bin/bash

if [ ! -d "deployer" ]; then
  git clone https://github.com/pronamic/deployer.git
fi

cd deployer

git pull

composer install

./bin/pronamic-deployer deploy pronamic-ideal https://github.com/pronamic/wp-pronamic-ideal.git -vvvv --non-interactive --to-s3 --to-wp-org
