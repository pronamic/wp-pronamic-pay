<?php

/**
 * Execute changes made in Pronamic Pay 2.0.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @since 2.0.0
 */

// Check if there is not already an upgrade running
if ( get_transient( 'pronamic_pay_upgrade_200' ) ) {
	return;
}

set_transient( 'pronamic_pay_upgrade_200', true, 3600 ); // 60 minutes

// Upgrade
global $wpdb;

require_once ABSPATH . '/wp-admin/includes/upgrade.php';

$charset_collate = '';
if ( ! empty( $wpdb->charset ) ) {
	$charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
}
if ( ! empty( $wpdb->collate ) ) {
	$charset_collate .= ' COLLATE ' . $wpdb->collate;
}

/*

-- You can undo the database upgrade by executing the following queries

UPDATE wp_pronamic_ideal_configurations SET post_id = null;
DELETE FROM wp_posts WHERE post_type = 'pronamic_gateway';

UPDATE wp_pronamic_ideal_payments SET post_id = null;
DELETE FROM wp_posts WHERE post_type = 'pronamic_payment';

UPDATE wp_rg_ideal_feeds SET post_id = null;
DELETE FROM wp_posts WHERE post_type = 'pronamic_pay_gf';

UPDATE wp_options SET option_value = 0 WHERE option_name = 'pronamic_pay_db_version';

DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );

*/

//////////////////////////////////////////////////
// Configs
//////////////////////////////////////////////////

global $pronamic_ideal;

$config_table = $wpdb->prefix . 'pronamic_ideal_configurations';

$sql = "CREATE TABLE $config_table (
	id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	post_id BIGINT(20) UNSIGNED NULL,
	variant_id VARCHAR(64) NULL,
	merchant_id VARCHAR(64) NULL,
	sub_id VARCHAR(64) NULL,
	mode VARCHAR(64) NULL,
	hash_key VARCHAR(64) NULL,
	private_key TEXT NULL,
	private_key_password VARCHAR(64) NULL,
	private_certificate TEXT NULL,
	meta LONGTEXT,
	PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );

// Query
$query = "
	SELECT
		*
	FROM
		$config_table
	WHERE
		post_id IS NULL
	LIMIT
		1
	;
";

$have_configs = true;

while ( $have_configs ) {
	$configs = $wpdb->get_results( $query ); // WPCS: unprepared SQL ok.

	$have_configs = ! empty( $configs );

	foreach ( $configs as $config ) {
		$title = sprintf( __( 'Configuration %d', 'pronamic_ideal' ), $config->id );

		$integration = $pronamic_ideal->get_integration( $config->variant_id );

		if ( $integration ) {
			$title = $integration->get_name();
		}

		// Post
		$post = array(
			'post_title'    => $title,
			'post_type'     => 'pronamic_gateway',
			'post_status'   => 'publish',
		);

		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			$wpdb->update(
				$config_table,
				array(
					'post_id' => $post_id,
				),
				array(
					'id' => $config->id,
				),
				'%d',
				'%d'
			);

			// Meta
			// We ignore (@) all notice of not existing properties
			$config_meta = json_decode( $config->meta );

			$meta = array();

			$meta['legacy_id'] = $config->id;
			$meta['id']        = $config->variant_id;
			$meta['mode']      = $config->mode;

			// iDEAL
			$meta['ideal_merchant_id'] = $config->merchant_id;
			$meta['ideal_sub_id']      = $config->sub_id;

			// iDEAL Basic
			$meta['ideal_hash_key'] = $config->hash_key;

			// iDEAL Advanced
			$meta['ideal_private_key']          = $config->private_key;
			$meta['ideal_private_key_password'] = $config->private_key_password;
			$meta['ideal_private_certificate']  = $config->private_certificate;

			// OmniKassa
			if ( 'rabobank-omnikassa' === $config->variant_id ) {
				$meta['omnikassa_merchant_id'] = $config->merchant_id;
				$meta['omnikassa_secret_key']  = $config->hash_key;

				$key_version = @$config_meta->keyVersion;
				// In Pronamic iDEAL v1.0 we stored the key version in the iDEAL sub ID
				$key_version = empty( $key_version ) ? $config->sub_id : $key_version;

				$meta['omnikassa_key_version'] = $key_version;

				unset( $meta['ideal_merchant_id'] );
				unset( $meta['ideal_hash_key'] );
			}

			// Buckaroo
			$meta['buckaroo_website_key']    = @$config_meta->buckarooWebsiteKey;
			$meta['buckaroo_secret_key']     = @$config_meta->buckarooSecretKey;

			// Icepay
			$meta['icepay_merchant_id']      = @$config_meta->icepayMerchantId;
			$meta['icepay_secret_code']      = @$config_meta->icepaySecretCode;

			// Mollie
			$meta['mollie_partner_id']       = @$config_meta->molliePartnerId;
			$meta['mollie_profile_key']      = @$config_meta->mollieProfileKey;

			// Sisow
			$meta['sisow_merchant_id']       = @$config_meta->sisowMerchantId;
			$meta['sisow_merchant_key']      = @$config_meta->sisowMerchantKey;

			// TargetPay
			$meta['targetpay_layout_code']   = @$config_meta->targetPayLayoutCode;

			// Qantani
			$meta['qantani_merchant_id']     = @$config_meta->qantani_merchant_id;
			$meta['qantani_merchant_key']    = @$config_meta->qantani_merchant_key;
			$meta['qantani_merchant_secret'] = @$config_meta->qantani_merchant_secret;

			// Ogone
			$meta['ogone_psp_id']              = @$config_meta->pspId;
			$meta['ogone_sha_in_pass_phrase']  = @$config_meta->shaInPassPhrase;
			$meta['ogone_sha_out_pass_phrase'] = @$config_meta->shaOutPassPhrase;
			$meta['ogone_user_id']             = @$config_meta->ogone_user_id;
			$meta['ogone_password']            = @$config_meta->ogone_password;

			// Other
			$meta['country']                 = @$config_meta->country;
			$meta['state_or_province']       = @$config_meta->stateOrProvince;
			$meta['locality']                = @$config_meta->locality;
			$meta['organization']            = @$config_meta->organization;
			$meta['organization_unit']       = @$config_meta->organizationUnit;
			$meta['common_name']             = @$config_meta->commonName;
			$meta['email']                   = @$config_meta->eMailAddress;

			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_key = '_pronamic_gateway_' . $key;

					update_post_meta( $post_id, $meta_key, $value );
				}
			}
		}
	}
}

//////////////////////////////////////////////////
// Config IDs map
//////////////////////////////////////////////////

$query = "
	SELECT
		id,
		post_id
	FROM
		$config_table
	;
";

$config_ids_map = array();

$config_ids = $wpdb->get_results( $query ); // WPCS: unprepared SQL ok.

foreach ( $config_ids as $config_id ) {
	$config_ids_map[ $config_id->id ] = $config_id->post_id;
}

//////////////////////////////////////////////////
// Gravity Forms payment feeds
//////////////////////////////////////////////////

$feeds_table = $wpdb->prefix . 'rg_ideal_feeds';

$sql = "CREATE TABLE $feeds_table (
	id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	post_id BIGINT(20) UNSIGNED NULL,
	form_id MEDIUMINT(8) UNSIGNED NOT NULL,
	configuration_id MEDIUMINT(8) UNSIGNED NOT NULL,
	is_active TINYINT(1) NOT NULL DEFAULT 1,
	meta LONGTEXT,
	PRIMARY KEY  (id),
	KEY form_id (form_id),
	KEY configuration_id (configuration_id)
) $charset_collate;";

dbDelta( $sql );

// Query
$query = "
	SELECT
		*
	FROM
		$feeds_table
	WHERE
		post_id IS NULL
	LIMIT
		1
	;
";

$have_feeds = true;

while ( $have_feeds ) {
	$feeds = $wpdb->get_results( $query ); // WPCS: unprepared SQL ok.

	$have_feeds = ! empty( $feeds );

	foreach ( $feeds as $feed ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Payment Form %d', 'pronamic_ideal' ), $feed->id ),
			'post_type'     => 'pronamic_pay_gf',
			'post_status'   => 'publish',
		);

		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			$wpdb->update(
				$feeds_table,
				array(
					'post_id' => $post_id,
				),
				array(
					'id' => $feed->id,
				),
				'%d',
				'%d'
			);

			// Meta
			// We ignore (@) all notice of not existing properties
			$meta = array();

			$feed_meta = json_decode( $feed->meta, true );

			$meta['form_id']                  = $feed->form_id;
			$meta['config_id']                = @$config_ids_map[ $feed->configuration_id ];
			$meta['is_active']                = $feed->is_active;
			$meta['transaction_description']  = @$feed_meta['transactionDescription'];
			$meta['delay_notification_ids']   = @$feed_meta['delayNotificationIds'];
			$meta['delay_admin_notification'] = @$feed_meta['delayAdminNotification'];
			$meta['delay_user_notification']  = @$feed_meta['delayUserNotification'];
			$meta['delay_post_creation']      = @$feed_meta['delayPostCreation'];
			$meta['condition_enabled']        = @$feed_meta['conditionEnabled'];
			$meta['condition_field_id']       = @$feed_meta['conditionFieldId'];
			$meta['condition_operator']       = @$feed_meta['conditionOperator'];
			$meta['condition_value']          = @$feed_meta['conditionValue'];
			$meta['user_role_field_id']       = @$feed_meta['userRoleFieldId'];
			$meta['fields']                   = @$feed_meta['fields'];
			$meta['links']                    = @$feed_meta['links'];

			if ( is_array( $meta['links'] ) ) {
				foreach ( $meta['links'] as &$link ) {
					if ( isset( $link['pageId'] ) ) {
						$link['page_id'] = $link['pageId'];
					}
				}
			}

			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_key = '_pronamic_pay_gf_' . $key;

					update_post_meta( $post_id, $meta_key, $value );
				}
			}
		}
	}
}

//////////////////////////////////////////////////
// Payments
//////////////////////////////////////////////////

$payments_table = $wpdb->prefix . 'pronamic_ideal_payments';

$sql = "CREATE TABLE $payments_table (
	id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	post_id BIGINT(20) UNSIGNED NULL,
	configuration_id MEDIUMINT(8) UNSIGNED NOT NULL,
	purchase_id VARCHAR(16) NULL,
	transaction_id VARCHAR(32) NULL,
	date_gmt DATETIME NOT NULL,
	amount DECIMAL(10, 2) NOT NULL,
	currency VARCHAR(8) NOT NULL,
	expiration_period VARCHAR(8) NOT NULL,
	language VARCHAR(8) NOT NULL,
	entrance_code VARCHAR(40) NULL,
	description TEXT NOT NULL,
	consumer_name VARCHAR(35) NULL,
	consumer_account_number VARCHAR(10) NULL,
	consumer_iban VARCHAR(34) NULL,
	consumer_bic VARCHAR(11) NULL,
	consumer_city VARCHAR(24) NULL,
	status VARCHAR(32) NULL DEFAULT NULL,
	status_requests MEDIUMINT(8) DEFAULT 0,
	source VARCHAR(32) NULL DEFAULT NULL,
	source_id VARCHAR(32) NULL DEFAULT NULL,
	email VARCHAR(128) NULL DEFAULT NULL,
	PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );

// Query

// We convert the payments in groups of 100 so not everything will load
// in memory at once
$query = "
	SELECT
		*
	FROM
		$payments_table
	WHERE
		post_id IS NULL
	LIMIT
		1
	;
";

$have_payments = true;

while ( $have_payments ) {
	$payments = $wpdb->get_results( $query ); // WPCS: unprepared SQL ok.

	$have_payments = ! empty( $payments );

	foreach ( $payments as $payment ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Payment %d', 'pronamic_ideal' ), $payment->id ),
			'post_date'     => get_date_from_gmt( $payment->date_gmt ),
			'post_date_gmt' => $payment->date_gmt,
			'post_type'     => 'pronamic_payment',
			'post_status'   => 'publish',
		);

		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			$wpdb->update(
				$payments_table,
				array(
					'post_id' => $post_id,
				),
				array(
					'id' => $payment->id,
				),
				'%d',
				'%d'
			);

			// Meta
			$meta = array(
				'config_id'               => @$config_ids_map[ $payment->configuration_id ],
				'purchase_id'             => $payment->purchase_id,
				'transaction_id'          => $payment->transaction_id,
				'currency'                => $payment->currency,
				'amount'                  => $payment->amount,
				'expiration_period'       => $payment->expiration_period,
				'language'                => $payment->language,
				'entrance_code'           => $payment->entrance_code,
				'description'             => $payment->description,
				'consumer_name'           => $payment->consumer_name,
				'consumer_account_number' => $payment->consumer_account_number,
				'consumer_iban'           => $payment->consumer_iban,
				'consumer_bic'            => $payment->consumer_bic,
				'consumer_city'           => $payment->consumer_city,
				'status'                  => $payment->status,
				'source'                  => $payment->source,
				'source_id'               => $payment->source_id,
				'email'                   => $payment->email,
			);

			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_key = '_pronamic_payment_' . $key;

					update_post_meta( $post_id, $meta_key, $value );
				}
			}
		}
	}
}

//////////////////////////////////////////////////
// Options config IDs
//////////////////////////////////////////////////

$options = array(
	// EventEspresso
	// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/EventEspresso/IDeal/AddOn.php#L72
	'pronamic_ideal_event_espresso_configuration_id' => 'pronamic_pay_ideal_event_espreso_config_id',
	// Jigoshop
	// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/Jigoshop/IDeal/IDealGateway.php#L62
	'jigoshop_pronamic_ideal_enabled'                => 'pronamic_pay_ideal_jigoshop_enabled',
	'jigoshop_pronamic_ideal_title'                  => 'pronamic_pay_ideal_jigoshop_title',
	'jigoshop_pronamic_ideal_description'            => 'pronamic_pay_ideal_jigoshop_description',
	'jigoshop_pronamic_ideal_configuration_id'       => 'pronamic_pay_ideal_jigoshop_config_id',
	// Membership
	'pronamic_ideal_membership_chosen_configuration' => 'pronamic_pay_ideal_membership_config_id',
	// s2Member
	// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/S2Member/Bridge/Settings.php#L52
	'pronamic_ideal_s2member_chosen_configuration'   => 'pronamic_pay_ideal_s2member_config_id',
	// WP e-Commerce
	// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/WPeCommerce/IDeal/IDealMerchant.php#L35
	'pronamic_ideal_wpsc_configuration_id'           => 'pronamic_pay_ideal_wpsc_config_id',
);

foreach ( $options as $key_old => $key_new ) {
	$value = get_option( $key_old );

	if ( ! empty( $value ) ) {
		$value_new = @$config_ids_map[ $value ];

		update_option( $key_new, $value_new );
	}
}

//////////////////////////////////////////////////
// Complex options config IDs
//////////////////////////////////////////////////

// Shopp
// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/Shopp/IDeal/GatewayModule.php#L72
$shopp_meta_table = $wpdb->prefix . 'shopp_meta';

// @see http://cube3x.com/2013/04/how-to-check-if-table-exists-in-wordpress-database/
if ( $shopp_meta_table === $wpdb->get_var( "SHOW TABLES LIKE '$shopp_meta_table';" ) ) { // WPCS: unprepared SQL ok.
	$query = "SELECT id, value FROM $shopp_meta_table WHERE type = 'setting' AND name = 'Pronamic_Shopp_IDeal_GatewayModule';";

	$row = $wpdb->get_row( $query ); // WPCS: unprepared SQL ok.

	if ( $row ) {
		$settings = maybe_unserialize( $row->value );

		if ( is_array( $settings ) && isset( $settings['pronamic_shopp_ideal_configuration'] ) ) {
			$value = $settings['pronamic_shopp_ideal_configuration'];

			$settings['config_id'] = @$config_ids_map[ $value ];

			$wpdb->update(
				$shopp_meta_table,
				array(
					'value' => serialize( $settings ),
				),
				array(
					'id' => $row->id,
				)
			);
		}
	}
}

// WooCommerce
// @see https://github.com/pronamic/wp-pronamic-ideal/blob/1.3.4/classes/Pronamic/WooCommerce/IDeal/IDealGateway.php#L42
$settings = get_option( 'woocommerce_pronamic_ideal_settings' );

if ( is_array( $settings ) && isset( $settings['configuration_id'] ) ) {
	$value = $settings['configuration_id'];

	$settings['config_id'] = @$config_ids_map[ $value ];

	unset( $settings['configuration_id'] );

	update_option( 'woocommerce_pronamic_pay_ideal_settings', $settings );
}

delete_transient( 'pronamic_pay_upgrade_200' );
