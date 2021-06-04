#!/bin/bash

# Pronamic Deployer.
if [ ! -d "deployer" ]; then
  git clone https://github.com/pronamic/deployer.git
fi

cd deployer

git pull

composer install --no-interaction

# Install AWS CLI
# https://docs.aws.amazon.com/cli/latest/userguide/install-cliv2-linux.html
if ! [ -x "$(command -v aws)" ]; then
  curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
  unzip awscliv2.zip
  sudo ./aws/install

  aws --version
fi

./bin/pronamic-deployer deploy pronamic-ideal https://github.com/pronamic/wp-pronamic-pay.git -vvvv --non-interactive --to-s3 --to-wp-org
