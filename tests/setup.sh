#!/bin/bash

# http://www.ltconsulting.co.uk/automated-wordpress-installation-with-bash-wp-cli/
# http://tomjn.com/2014/03/01/wordpress-bash-magic/
# http://stackoverflow.com/questions/4665051/check-if-passed-argument-is-file-or-directory-in-bash

CWD=$(pwd)

PLUGIN_DIR_WORKING=$(pwd)
PLUGIN_DIR_TEST="$WP_PAY_TEST_DIR/wp-content/plugins/pronamic-ideal"

# Create test dir if not exists
if [ ! -d "$WP_PAY_TEST_DIR" ]; then
	mkdir $WP_PAY_TEST_DIR
fi

# Go into the test dir
cd $WP_PAY_TEST_DIR

# Install WordPress if not installed
if ! $(wp core is-installed); then
	wp core download --path=$WP_PAY_TEST_DIR --locale=$WP_PAY_TEST_LOCALE

	wp core config --dbname=$WP_PAY_TEST_DB_NAME --dbuser=$WP_PAY_TEST_DB_USER --dbpass=$WP_PAY_TEST_DB_PASSWORD --locale=$WP_PAY_TEST_LOCALE

	wp db create

	wp core install --url=$WP_PAY_TEST_URL --title=wp-e-commerce.dev --admin_user=$WP_PAY_TEST_USER --admin_password=$WP_PAY_TEST_PASSWORD --admin_email=$WP_PAY_TEST_EMAIL
fi

# Symlink plugin
if [ ! -e "$PLUGIN_DIR_TEST" ]; then
	ln -s $PLUGIN_DIR_WORKING $PLUGIN_DIR_TEST
fi

# Activate plugins
wp plugin activate pronamic-ideal

wp plugin install wp-e-commerce --activate=1
wp plugin install shopp --activate=1
wp plugin install gravityforms-nl --activate=1
wp plugin install https://github.com/gravityforms/gravityforms/archive/1.9.5.12.zip --activate=1
wp plugin install ithemes-exchange --activate=1
wp plugin install membership --activate=1

# Create gateway configration
post_id=$(wp post create --post_type=pronamic_gateway --post_status=publish --post_title='MultiSafepay' --porcelain)

wp post meta set $post_id '_pronamic_gateway_id' 'multisafepay-connect'
wp post meta set $post_id '_pronamic_gateway_mode' 'test'
wp post meta set $post_id '_pronamic_gateway_multisafepay_account_id' $MULTISAFEPAY_ACCOUNT_ID
wp post meta set $post_id '_pronamic_gateway_multisafepay_site_id' $MULTISAFEPAY_SITE_ID
wp post meta set $post_id '_pronamic_gateway_multisafepay_site_code' $MULTISAFEPAY_SECURE_CODE

# iThemes Exchnage
wp option update 'pronamic_ithemes_exchange_ideal_addon_configuration' $post_id

# Membership
wp option update 'pronamic_pay_membership_config_id' $post_id

# WP e-Commerce
wp option update 'pronamic_pay_ideal_wpsc_config_id' $post_id
