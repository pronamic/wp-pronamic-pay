#!/bin/bash

cd `dirname $0`
cd ..

composer install --no-interaction

# Install AWS CLI
# https://docs.aws.amazon.com/cli/latest/userguide/install-cliv2-linux.html
if ! [ -x "$(command -v aws)" ]; then
  curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
  unzip awscliv2.zip
  sudo ./aws/install

  aws --version
fi

./vendor/bin/wp-deployer deploy pronamic-ideal https://github.com/pronamic/wp-pronamic-pay.git -vvvv --branch main --non-interactive --to-s3 --to-wp-org
./vendor/bin/wp-deployer deploy pronamic-pay-adyen https://github.com/pronamic/wp-pronamic-pay-adyen.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-buckaroo https://github.com/pronamic/wp-pronamic-pay-buckaroo.git -vvvv --branch master --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-charitable https://github.com/pronamic/wp-pronamic-pay-charitable.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-contact-form-7 https://github.com/pronamic/wp-pronamic-pay-contact-form-7.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-digiwallet https://github.com/pronamic/wp-pronamic-pay-digiwallet.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-easy-digital-downloads https://github.com/pronamic/wp-pronamic-pay-easy-digital-downloads.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-event-espresso https://github.com/pronamic/wp-pronamic-pay-event-espresso.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-formidable-forms https://github.com/pronamic/wp-pronamic-pay-formidable-forms.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-fundraising https://github.com/pronamic/wp-pronamic-pay-fundraising.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-give https://github.com/pronamic/wp-pronamic-pay-give.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-gravityforms https://github.com/pronamic/wp-pronamic-pay-gravityforms.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-memberpress https://github.com/pronamic/wp-pronamic-pay-memberpress.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-mollie https://github.com/pronamic/wp-pronamic-pay-mollie.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-ninjaforms https://github.com/pronamic/wp-pronamic-pay-ninjaforms.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-paypal https://github.com/pronamic/wp-pronamic-pay-paypal.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-restrict-content-pro https://github.com/pronamic/wp-pronamic-pay-restrict-content-pro.git -vvvv --branch main --non-interactive --to-s3
./vendor/bin/wp-deployer deploy pronamic-pay-woocommerce https://github.com/pronamic/wp-pronamic-pay-woocommerce.git -vvvv --branch main --non-interactive --to-s3
