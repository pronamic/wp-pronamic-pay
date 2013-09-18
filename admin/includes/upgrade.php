<?php

/**
 * Execute changes made in Pronamic iDEAL 1.4.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @since 1.4.0
 */
function orbis_ideal_upgrade_140() {
	global $wpdb;

	require_once ABSPATH . '/wp-admin/includes/upgrade.php';
	
	global $wpdb;
	
	$charset_collate = '';
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
	}
	if ( ! empty( $wpdb->collate ) ) {
		$charset_collate .= ' COLLATE ' . $wpdb->collate;
	}

	/*
	UPDATE wp_pronamic_ideal_configurations SET post_id = null;
	DELETE FROM wp_posts WHERE post_type = 'pronamic_gateway';

	UPDATE wp_pronamic_ideal_payments SET post_id = null;
	DELETE FROM wp_posts WHERE post_type = 'pronamic_payment';

	UPDATE wp_rg_ideal_feeds SET post_id = null;
	DELETE FROM wp_posts WHERE post_type = 'pronamic_pay_gf';

	UPDATE wp_options SET option_value = 0 WHERE option_name = 'pronamic_ideal_db_version';

	DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );
	*/

	// Configurations
	$configurations_table = $wpdb->prefix . 'pronamic_ideal_configurations';

	$sql = "CREATE TABLE $configurations_table (
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
			$configurations_table
		WHERE
			post_id IS NULL
		;
	";
	
	$configurations = $wpdb->get_results( $query );
	$ids_map        = array();

	foreach ( $configurations as $configuration ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Configuration %d', 'pronamic_ideal' ), $configuration->id ),
			'post_type'     => 'pronamic_gateway',
			'post_status'   => 'publish'
		);
		
		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			$ids_map[$configuration->id] = $post_id;

			$configuration_meta = json_decode( $configuration->meta );

			// Meta
			// We ignore (@) all notice of not existing properties
			$meta = array();

			$meta['legacy_id']            = $configuration->id;
			$meta['id']                   = $configuration->variant_id;
			$meta['mode']                 = $configuration->mode;

			// iDEAL
			$meta['ideal_merchant_id']    = $configuration->merchant_id;
			$meta['ideal_sub_id']         = $configuration->sub_id;
					
			// iDEAL Basic
			$meta['ideal_hash_key'] = $configuration->hash_key;
					
			// iDEAL Advanced
			$meta['ideal_private_key']          = $configuration->private_key;
			$meta['ideal_private_key_password'] = $configuration->private_key_password;
			$meta['ideal_private_certificate']  = $configuration->private_certificate;
					
			// OmniKassa
			if ( $configuration->variant_id == 'rabobank-omnikassa' ) {
				$meta['omnikassa_merchant_id'] = @$configuration_meta->merchant_id;
				$meta['omnikassa_secret_key']  = @$configuration_meta->hash_key;
				$meta['omnikassa_key_version'] = @$configuration_meta->keyVersion;
			}
				
			// Buckaroo
			$meta['buckaroo_website_key']    = @$configuration_meta->buckarooWebsiteKey;
			$meta['buckaroo_secret_key']     = @$configuration_meta->buckarooSecretKey;
			
			// Icepay
			$meta['icepay_merchant_id']      = @$configuration_meta->icepayMerchantId;
			$meta['icepay_secret_code']      = @$configuration_meta->icepaySecretCode;
			
			// Mollie
			$meta['mollie_partner_id']       = @$configuration_meta->molliePartnerId;
			$meta['mollie_profile_key']      = @$configuration_meta->mollieProfileKey;
			
			// Sisow
			$meta['sisow_merchant_id']       = @$configuration_meta->sisowMerchantId;
			$meta['sisow_merchant_key']      = @$configuration_meta->sisowMerchantKey;
			
			// TargetPay
			$meta['targetpay_layout_code']   = @$configuration_meta->targetPayLayoutCode;
			
			// Qantani
			$meta['qantani_merchant_id']     = @$configuration_meta->qantani_merchant_id;
			$meta['qantani_merchant_key']    = @$configuration_meta->qantani_merchant_key;
			$meta['qantani_merchant_secret'] = @$configuration_meta->qantani_merchant_secret;

			// Ogone
			$meta['ogone_psp_id']            = @$configuration_meta->pspId;
			$meta['ogone_sha_in']            = @$configuration_meta->shaInPassPhrase;
			$meta['ogone_sha_out']           = @$configuration_meta->shaOutPassPhrase;
			$meta['ogone_user_id']           = @$configuration_meta->ogone_user_id;
			$meta['ogone_password']          = @$configuration_meta->ogone_password;

			// Other
			$meta['country']                 = @$configuration_meta->country;
			$meta['state_or_province']       = @$configuration_meta->stateOrProvince;
			$meta['locality']                = @$configuration_meta->locality;
			$meta['organization']            = @$configuration_meta->organization;
			$meta['organization_unit']       = @$configuration_meta->organizationUnit;
			$meta['common_name']             = @$configuration_meta->commonName;
			$meta['email']                   = @$configuration_meta->eMailAddress;
			
			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_key = '_pronamic_gateway_' . $key;

					update_post_meta( $post_id, $meta_key, $value );
				}
			}
		
			$wpdb->update( $configurations_table, array( 'post_id' => $post_id ), array( 'id' => $configuration->id ), '%d', '%d' );
		}
	}
	
	// Gateway ID options
	$options = array(
		'woocommerce_pronamic_ideal_settings' => array(
			'type' => 'object',
			'var'  => 'configuration_id'
		),
		'pronamic_ideal_event_espresso_configuration_id' => array(
			'type' => 'var'
		),
		'pronamic_ideal_wpsc_configuration_id' => array(
			'type' => 'var'
		),
		'jigoshop_pronamic_ideal_configuration_id' => array(
			'type' => 'var'
		)
	);
	
	foreach ( $options as $option => $data ) {
		$value = get_option( $option );
		
		if ( ! empty ( $value ) ) {
			if ( isset( $data['type'] ) ) {
				switch( $data['type'] ) {
					case 'var':
						if ( isset( $ids_map[$value] ) ) {
							update_option( $option, $ids_map[$value] );
						}
						
						break;
				}
			}
		}
	}

	// Gravity Forms feeds
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
		;
	";
	
	$feeds = $wpdb->get_results( $query );
	
	foreach ( $feeds as $feed ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Feed %d', 'pronamic_ideal' ), $feed->id ),
			'post_type'     => 'pronamic_pay_gf',
			'post_status'   => 'publish'
		);
		
		$post_id = wp_insert_post( $post );
		
		if ( $post_id ) {
			// Meta
			// We ignore (@) all notice of not existing properties
			$meta = array();

			$feed_meta = json_decode( $feed->meta );
			
			$meta['form_id']                  = $feed->form_id;
			$meta['configuration_id']         = @$ids_map[$feed->configuration_id];
			$meta['is_active']                = $feed->is_active;
			$meta['transaction_description']  = @$feed_meta->transactionDescription;
			$meta['delay_notification_ids']   = @$feed_meta->delayNotificationIds;
			$meta['delay_admin_motification'] = @$feed_meta->delayAdminNotification;
			$meta['delay_users_motification'] = @$feed_meta->delayUserNotification;
			$meta['delay_post_creation']      = @$feed_meta->delayUserNotification;
			$meta['condition_enabled']        = @$feed_meta->conditionEnabled;
			$meta['condition_field_id']       = @$feed_meta->conditionFieldId;
			$meta['condition_operator']       = @$feed_meta->conditionOperator;
			$meta['condition_value']          = @$feed_meta->conditionValue;
			$meta['user_role_field_id']       = @$feed_meta->userRoleFieldId;
			$meta['fields']                   = @$feed_meta->fields;
			$meta['links']                    = (array) @$feed_meta->links;
			
			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_key = '_pronamic_pay_gf_' . $key;

					update_post_meta( $post_id, $meta_key, $value );
				}
			}
		
			$wpdb->update( $feeds_table, array( 'post_id' => $post_id ), array( 'id' => $feed->id ), '%d', '%d' );
		}
	}

	// Payments
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
		PRIMARY KEY  (id),
		KEY configuration_id (configuration_id),
		UNIQUE (entrance_code)
	) $charset_collate;";
	
	dbDelta( $sql );

	// Query
	$query = "
		SELECT
			*
		FROM
			$payments_table
		WHERE
			post_id IS NULL
		;
	";

	$payments = $wpdb->get_results( $query );

	foreach ( $payments as $payment ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Payment %d', 'pronamic_ideal' ), $payment->id ),
			'post_date_gmt' => $payment->date_gmt,
			'post_type'     => 'pronamic_payment',
			'post_status'   => 'publish'
		);
		
		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			// Meta 
			$meta = array(
				'purchase_id'             => $payment->purchase_id,
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
		
			$wpdb->update( $payments_table, array( 'post_id' => $post_id ), array( 'id' => $payment->id ), '%d', '%d' );
		}
	}
}
