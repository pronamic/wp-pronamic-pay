#!/bin/bash

cd `dirname $0`
cd ../vendor/pronamic/wp-deployer || exit

# Install AWS CLI
# https://docs.aws.amazon.com/cli/latest/userguide/install-cliv2-linux.html
if ! [ -x "$(command -v aws)" ]; then
  curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
  unzip awscliv2.zip
  sudo ./aws/install

  aws --version
fi

./bin/wp-deployer deploy pronamic-ideal https://github.com/pronamic/wp-pronamic-pay.git -vvvv --branch main --non-interactive --to-s3 --to-wp-org
./bin/wp-deployer deploy pronamic-pay-adyen https://github.com/pronamic/wp-pronamic-pay-adyen.git -vvvv --branch main --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-contact-form-7 https://github.com/pronamic/wp-pronamic-pay-contact-form-7.git -vvvv --branch main --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-digiwallet https://github.com/pronamic/wp-pronamic-pay-digiwallet.git -vvvv --branch main --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-fundraising https://github.com/pronamic/wp-pronamic-pay-fundraising.git -vvvv --branch main --non-interactive --to-s3
./bin/wp-deployer deploy pronamic-pay-paypal https://github.com/pronamic/wp-pronamic-pay-paypal.git -vvvv --branch main --non-interactive --to-s3
