#!/bin/bash

# Pronamic Deployer.
if [ ! -d "deployer" ]; then
  git clone https://github.com/pronamic/deployer.git
fi

cd deployer || exit

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

./bin/wp-deployer deploy pronamic-ideal https://github.com/pronamic/wp-pronamic-pay.git -vvvv --non-interactive --to-s3 --to-wp-org
./bin/wp-deployer deploy pronamic-pay-contact-form-7 https://github.com/wp-pay-extensions/contact-form-7.git -vvvv --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-adyen https://github.com/wp-pay-gateways/adyen.git -vvvv --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-digiwallet https://github.com/wp-pay-gateways/digiwallet.git -vvvv --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-payvision https://github.com/wp-pay-gateways/payvision.git -vvvv --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-fundraising https://github.com/wp-pay/fundraising.git -vvvv --non-interactive --to-s3
