<?php

$update = null;
$error = null;

// Configuration
if ( empty( $_POST ) ) {
	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
} else {
	$id = filter_input( INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING );
}

$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );
if ( $configuration == null ) {
	$configuration = new Pronamic_WordPress_IDeal_Configuration();
}

// Generator
if ( empty( $configuration->numberDaysValid ) ) {
	$configuration->numberDaysValid = 365 * 5;
}

if ( empty( $configuration->country ) ) {
	$language = get_option( 'WPLANG', WPLANG );

	$configuration->countryName = substr( $language, 3 );
}

if ( empty( $configuration->organization ) ) {
	$configuration->organization = get_bloginfo( 'name' );
}

if ( empty( $configuration->eMailAddress ) ) {
	$configuration->eMailAddress = get_bloginfo( 'admin_email' );
}

// Sections
$variant_id = $configuration->getVariant() == null ? '' : $configuration->getVariant()->getId();

$options = array();

foreach ( Pronamic_WordPress_IDeal_ConfigurationsRepository::getProviders() as $provider ) {
	$group = array(
		'name'    => $provider->getName(),
		'options' => array()
	);

	foreach ( $provider->getVariants() as $variant ) {
		$group['options'][ $variant->getId() ] = $variant->getName();
	}

	$options[] = $group;
}

$sections = array(
	array(
		'title'  => __( 'General', 'pronamic_ideal' ),
		'fields' => array(
			/*array(
				'id'          => 'pronamic_ideal_variant_id',
				'title'       => __( 'Variant', 'pronamic_ideal' ),
				'type'        => 'select',
				'value'       => $variant_id,
				'options'     => $options
			),*/
			array(
				'meta_key'    => '_pronamic_gateway_mode',
				'name'        => 'mode',
				'id'          => 'pronamic_ideal_mode',
				'title'       => __( 'Mode', 'pronamic_ideal' ),
				'type'        => 'optgroup',
				'options'     => array(
					Pronamic_IDeal_IDeal::MODE_LIVE => __( 'Live', 'pronamic_ideal' ),
					Pronamic_IDeal_IDeal::MODE_TEST => __( 'Test', 'pronamic_ideal' ),
				)
			)
		)
	),
	array(
		'title'   => __( 'iDEAL', 'pronamic_ideal' ),
		'methods' => array( 'basic', 'advanced', 'advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_merchant_id',
				'name'        => 'merchantId',
				'id'          => 'pronamic_ideal_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => __( 'You receive the merchant ID (also known as: acceptant ID) from your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'basic', 'advanced', 'advanced_v3' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_sub_id',
				'name'        => 'subId',
				'id'          => 'pronamic_ideal_sub_id',
				'title'       => __( 'Sub ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'small-text', 'code' ),
				'description' => sprintf( __( 'You receive the sub ID from your iDEAL provider, the default is: %s.', 'pronamic_ideal' ), 0 ),
				'methods'     => array( 'basic', 'advanced', 'advanced_v3' )
			)
		)
	),
	array(
		'title'   => __( 'Basic', 'pronamic_ideal' ),
		'methods' => array( 'basic' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_hash_key',
				'name'        => 'hashKey',
				'id'          => 'pronamic_ideal_hash_key',
				'title'       => __( 'Hash Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the hash key (also known as: key or secret key) in the iDEAL dashboard of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'basic' )
			),
			array(
				'id'          => 'pronamic_ideal_basic_xml_notification_url',
				'title'       => __( 'XML Notification URL', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'value'       => add_query_arg( array(
					'gateway'         => 'ideal_basic',
					'xml_notifaction' => 'true'
				), site_url( '/' ) ),
				'methods'     => array( 'basic' ),
				'readonly'    => true
			)
		)
	),
	array(
		'title'   => __( 'Mollie', 'pronamic_ideal' ),
		'methods' => array( 'mollie' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_mollie_partner_id',
				'name'        => 'molliePartnerId',
				'id'          => 'pronamic_ideal_mollie_partner_id',
				'title'       => __( 'Partner ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => __( 'Mollie.nl accountnummer. Op het gespecificeerde account wordt na succesvolle betaling tegoed bijgeschreven.', 'pronamic_ideal' ),
			),
			 array(
				'meta_key'    => '_pronamic_gateway_mollie_profile_key',
				'name'        => 'mollieProfileKey',
				'id'          => 'pronamic_ideal_mollie_profile_key',
				'title'       => __( 'Profile Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf(
					__( 'Hiermee kunt u een ander websiteprofielen selecteren om uw betaling aan te linken. Gebruik de waarde uit het veld Key uit het profiel overzicht. [<a href="%s" target="_blank">bekijk overzicht van uw profielen</a>].', 'pronamic_ideal' ),
					'https://www.mollie.nl/beheer/account/profielen/'
				)
			)
		)
	),
	array(
		'title'   => __( 'OmniKassa', 'pronamic_ideal' ),
		'methods' => array( 'omnikassa' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_merchant_id',
				'name'        => 'keyVersion',
				'id'          => 'pronamic_ideal_omnikassa_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_secret_key',
				'name'        => 'keyVersion',
				'id'          => 'pronamic_ideal_omnikassa_secret_key',
				'title'       => __( 'Secret Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_key_version',
				'name'        => 'keyVersion',
				'id'          => 'pronamic_ideal_key_version',
				'title'       => __( 'Key Version', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'small-text', 'code' ),
				'description' => sprintf( __( 'You can find the key version in the <a href="%s" target="_blank">OmniKassa Download Dashboard</a>.', 'pronamic_ideal' ), 'https://download.omnikassa.rabobank.nl/' ),
			)
		)
	),
	array(
		'title'   => __( 'Buckaroo', 'pronamic_ideal' ),
		'methods' => array( 'buckaroo' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_buckaroo_website_key',
				'name'        => 'buckarooWebsiteKey',
				'id'          => 'pronamic_ideal_buckaroo_website_key',
				'title'       => __( 'Website Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf( __( 'You can find your Buckaroo website keys in the <a href="%s" target="_blank">Buckaroo Payment Plaza</a> under "Profile" » "Website".', 'pronamic_ideal' ), 'https://payment.buckaroo.nl/' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_buckaroo_secret_key',
				'name'        => 'buckarooSecretKey',
				'id'          => 'pronamic_ideal_buckaroo_secret_key',
				'title'       => __( 'Secret Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf( __( 'You can find your Buckaroo secret key in the <a href="%s" target="_blank">Buckaroo Payment Plaza</a> under "Configuration" » "Secret Key for Digital Signature".', 'pronamic_ideal' ), 'https://payment.buckaroo.nl/' )
			)
		)
	),
	array(
		'title'   => __( 'ICEPAY', 'pronamic_ideal' ),
		'methods' => array( 'icepay' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_icepay_merchant_id',
				'name'        => 'icepayMerchantId',
				'id'	      => 'pronamic_ideal_icepay_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => sprintf(
					__( 'You can find your Merchant ID on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
					'https://www.icepay.com/NL/Login',
					'https://www.icepay.com/Merchant/NL/Websites'
				)
			),
			array(
				'meta_key'    => '_pronamic_gateway_icepay_secret_code',
				'name'        => 'icepaySecretCode',
				'id'          => 'pronamic_ideal_icepay_secret_code',
				'title'       => __( 'Secret Code', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf(
					__( 'You can find your Secret Code on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
					'https://www.icepay.com/NL/Login',
					'https://www.icepay.com/Merchant/NL/Websites'
				)
			)
		)
	),
	array(
		'title'   => __( 'Sisow', 'pronamic_ideal' ),
		'methods' => array( 'sisow' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_sisow_merchant_id',
				'name'        => 'sisowMerchantId',
				'id'          => 'pronamic_ideal_sisow_merchant_id',
				'title'       => _x( 'Merchant ID', 'sisow', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf(
					__( 'You can find your Merchant ID on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
					'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
					'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
				)
			),
			 array(
				'meta_key'    => '_pronamic_gateway_sisow_merchant_key',
				'name'        => 'sisowMerchantKey',
				'id'          => 'pronamic_ideal_sisow_merchant_key',
				'title'       => _x( 'Merchant Key', 'sisow', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf(
					__( 'You can find your Merchant Key on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
					'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
					'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
				)
			)
		)
	),
	array(
		'title'   => __( 'TargetPay', 'pronamic_ideal' ),
		'methods' => array( 'targetpay' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_targetpay_layoutcode',
				'name'        => 'targetPayLayoutCode',
				'id'          => 'pronamic_ideal_targetpay_layoutcode',
				'title'       => __( 'Layout Code', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'De layoutcode waarop de betaling geboekt moet worden. Zie subaccounts.', 'pronamic_ideal' ),
			)
		)
	),
	array(
		'title'   => __( 'Ogone', 'pronamic_ideal' ),
		'methods' => array( 'easy', 'internetkassa', 'ogone_directlink' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ogone_pspid',
				'name'        => 'pspId',
				'id'          => 'pronamic_ideal_pspid',
				'title'       => __( 'PSPID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf(
						__( 'If you use the ABN AMRO - IDEAL Easy variant you can use <code>%s</code>.', 'pronamic_ideal' ),
						'TESTiDEALEASY'
				),
				'methods'     => array( 'easy', 'internetkassa', 'ogone_directlink' )
			),
			array(
				'id'          => 'pronamic_ideal_character_encoding',
				'title'       => __( 'Character encoding', 'pronamic_ideal' ),
				'type'        => 'text',
				'value'       => get_bloginfo( 'charset' ),
				'methods'     => array( 'internetkassa' ),
				'readonly'    => true
			),
			 array(
				'id'          => 'pronamic_ideal_hash_algorithm',
				'title'       => __( 'Hash algorithm', 'pronamic_ideal' ),
				'type'        => 'text',
				'value'       => 'SHA-1',
				'methods'     => array( 'internetkassa' ),
				'readonly'    => true
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_sha_in',
				'name'        => 'shaInPassPhrase',
				'id'          => 'pronamic_ideal_sha_in_pass_phrase',
				'title'       => __( 'SHA-IN Pass phrase', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the SHA-IN Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Data and origin verification) of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'internetkassa', 'ogone_directlink' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_sha_out',
				'name'        => 'shaOutPassPhrase',
				'id'          => 'pronamic_ideal_sha_out_pass_phrase',
				'title'       => __( 'SHA-OUT Pass phrase', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the SHA-OUT Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Transaction feedback) of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'internetkassa' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_user_id',
				'name'        => 'ogone_user_id',
				'id'          => 'pronamic_ideal_ogone_user_id',
				'title'       => __( 'User ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'methods'     => array( 'ogone_directlink' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_password',
				'name'        => 'ogone_password',
				'id'          => 'pronamic_ideal_ogone_password',
				'title'       => __( 'Password', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'methods'     => array( 'ogone_directlink' )
			)
		)
	),
	array(
		'title'   => __( 'Qantani', 'pronamic_ideal' ),
		'methods' => array( 'qantani' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_id',
				'name'        => 'qantani_merchant_id',
				'id'          => 'pronamic_ideal_qantani_merchant_id',
				'title'       => _x( 'Merchant ID', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_secret',
				'name'        => 'qantani_merchant_secret',
				'id'          => 'pronamic_ideal_qantani_merchant_secret',
				'title'       => _x( 'Secret', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_key',
				'name'        => 'qantani_merchant_key',
				'id'          => 'pronamic_ideal_qantani_merchant_key',
				'title'       => _x( 'Key', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' )
			)
		)
	),
	array(
		'title'   => __( 'Advanced', 'pronamic_ideal' ),
		'methods' => array( 'advanced', 'advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_key_password',
				'name'        => 'privateKeyPassword',
				'id'          => 'pronamic_ideal_private_key_password',
				'title'       => __( 'Private Key Password', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_key',
				'name'        => 'privateKey',
				'id'          => 'pronamic_ideal_private_key',
				'title'       => __( 'Private Key', 'pronamic_ideal' ),
				'type'        => 'file',
				'callback'    => 'pronamic_ideal_private_key_field'
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_certificate',
				'name'        => 'privateCertificate',
				'id'          => 'pronamic_ideal_private_certificate',
				'title'       => __( 'Private Certificate', 'pronamic_ideal' ),
				'type'        => 'file',
				'callback'    => 'pronamic_ideal_private_certificate_field'
			)
		)
	),
	array(
		'title'   => __( 'Private Key and Certificate', 'pronamic_ideal' ),
		'methods' => array( 'advanced', 'advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_number_days_valid',
				'name'        => 'numberDaysValid',
				'id'          => 'pronamic_ideal_number_days_valid',
				'title'       => __( 'Number Days Valid', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'specify the length of time for which the generated certificate will be valid, in days.', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_country',
				'name'        => 'country',
				'id'          => 'pronamic_ideal_country',
				'title'       => __( 'Country', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( '2 letter code [NL]', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_state_or_province',
				'name'        => 'stateOrProvince',
				'id'          => 'pronamic_ideal_state_or_province',
				'title'       => __( 'State or Province', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'full name [Friesland]', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_locality',
				'name'        => 'locality',
				'id'          => 'pronamic_ideal_locality',
				'title'       => __( 'Locality', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, city', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_organization',
				'name'        => 'organization',
				'id'          => 'pronamic_ideal_organization',
				'title'       => __( 'Organization', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, company [Pronamic]', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_',
				'name'        => 'organizationUnit',
				'id'          => 'pronamic_ideal_organization_unit',
				'title'       => __( 'Organization Unit', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, section', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_common_name',
				'name'        => 'commonName',
				'id'          => 'pronamic_ideal_common_name',
				'title'       => __( 'Common Name', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' =>
					__( 'eg, YOUR name', 'pronamic_ideal' ) . '<br />' .
					__( 'Do you have an iDEAL subscription with Rabobank or ING Bank, please fill in the domainname of your website.', 'pronamic_ideal' ) . '<br />' .
					__( 'Do you have an iDEAL subscription with ABN AMRO, please fill in "ideal_<strong>company</strong>", where "company" is your company name (as specified in the request for the subscription). The value must not exceed 25 characters.', 'pronamic_ideal' )
			),
			array(
				'meta_key'    => '_pronamic_gateway_email',
				'name'        => 'eMailAddress',
				'id'          => 'pronamic_ideal_email_address',
				'title'       => __( 'Email Address', 'pronamic_ideal' ),
				'type'        => 'text'
			)
		)
	)
);

// Request
if ( ! empty( $_POST ) && check_admin_referer( 'pronamic_ideal_save_configuration', 'pronamic_ideal_nonce' ) ) {
	$variantId = filter_input( INPUT_POST, 'pronamic_ideal_variant_id', FILTER_SANITIZE_STRING );
	$variant = Pronamic_WordPress_IDeal_ConfigurationsRepository::getVariantById( $variantId );

	$configuration->setVariant( $variant );

	foreach ( $sections as $section ) {
		foreach ( $section['fields'] as $field ) {
			if ( isset( $field['name'], $field['id'] ) ) {
				$property = $field['name'];
				$name     = $field['id'];
				$value    = $configuration->$property;

				if ( $field['type'] == 'file' ) {
					if ( $_FILES[ $name ]['error'] == UPLOAD_ERR_OK ) {
						$value = file_get_contents( $_FILES[ $name ]['tmp_name'] );
					}
				} else {
					$value = filter_input( INPUT_POST, $name, FILTER_SANITIZE_STRING );
				}

				$configuration->$property = $value;
			}
		}
	}

	// Generator
	$configuration->numberDaysValid  = filter_input( INPUT_POST, 'pronamic_ideal_number_days_valid', FILTER_SANITIZE_STRING );
	$configuration->country          = filter_input( INPUT_POST, 'pronamic_ideal_country', FILTER_SANITIZE_STRING );
	$configuration->stateOrProvince  = filter_input( INPUT_POST, 'pronamic_ideal_state_or_province', FILTER_SANITIZE_STRING );
	$configuration->locality         = filter_input( INPUT_POST, 'pronamic_ideal_locality', FILTER_SANITIZE_STRING );
	$configuration->organization     = filter_input( INPUT_POST, 'pronamic_ideal_organization', FILTER_SANITIZE_STRING );
	$configuration->organizationUnit = filter_input( INPUT_POST, 'pronamic_ideal_organization_unit', FILTER_SANITIZE_STRING );
	$configuration->commonName       = filter_input( INPUT_POST, 'pronamic_ideal_common_name', FILTER_SANITIZE_STRING );
	$configuration->eMailAddress     = filter_input( INPUT_POST, 'pronamic_ideal_email_address', FILTER_SANITIZE_STRING );

	if ( isset( $_POST['generate'] ) ) {
		$dn = array();

		if ( ! empty( $configuration->country ) ) {
			$dn['countryName'] = $configuration->country;
		}

		if ( ! empty( $configuration->stateOrProvince ) ) {
			$dn['stateOrProvinceName'] = $configuration->stateOrProvince;
		}

		if ( ! empty( $configuration->locality ) ) {
			$dn['localityName'] = $configuration->locality;
		}

		if ( ! empty( $configuration->organization ) ) {
			$dn['organizationName'] = $configuration->organization;
		}

		if ( ! empty( $configuration->organizationUnit ) ) {
			$dn['organizationalUnitName'] = $configuration->organizationUnit;
		}

		if ( ! empty( $configuration->commonName ) ) {
			$dn['commonName'] = $configuration->commonName;
		}

		if ( ! empty( $configuration->eMailAddress ) ) {
			$dn['emailAddress'] = $configuration->eMailAddress;
		}

		$configargs = array(
			'private_key_bits'   => 1024,
			'private_key_type'   => OPENSSL_KEYTYPE_RSA,
			'encrypt_key'        => false
			// 'encrypt_key_cipher' => OPENSSL_CIPHER_AES_128_CBC
		);

		$privateKeyResource = openssl_pkey_new( $configargs );
		if ( $privateKeyResource !== false ) {

			$csr = openssl_csr_new( $dn, $privateKeyResource, $configargs );

			$certificateResource = openssl_csr_sign( $csr, null, $privateKeyResource, $configuration->numberDaysValid, $configargs );

			if ( $certificateResource !== false ) {
				$privateKeyPassword = filter_input( INPUT_POST, 'pronamic_ideal_generate_private_key_password', FILTER_SANITIZE_STRING );

				$privateCertificate = null;
				$exportedCertificate = openssl_x509_export( $certificateResource, $privateCertificate );

				$privateKey = null;
				$exportedKey = openssl_pkey_export( $privateKeyResource, $privateKey, $privateKeyPassword, $configargs );

				if ( $exportedCertificate && $exportedKey ) {
					$configuration->privateKey         = $privateKey;
					$configuration->privateKeyPassword = $privateKeyPassword;
					$configuration->privateCertificate = $privateCertificate;
				}
			} else {
				$error = __( 'Unfortunately we could not generate a certificate resource from the given CSR (Certificate Signing Request).', 'pronamic_ideal' );
			}
		} else {
			$error = __( 'Unfortunately we could not generate a private key.', 'pronamic_ideal' );
		}
	}

	// Update
	$updated = Pronamic_WordPress_IDeal_ConfigurationsRepository::updateConfiguration( $configuration );

	if ( $updated ) {
		// Transient
		Pronamic_WordPress_IDeal_IDeal::deleteConfigurationTransient( $configuration );

		$update = sprintf(
			__( 'Configuration updated, %s.', 'pronamic_ideal' ),
			sprintf(
				__( '<a href="%s">back to overview</a>', 'pronamic_ideal' ),
				Pronamic_WordPress_IDeal_Admin::getConfigurationsLink()
			)
		);
	} elseif ( $updated === false ) {
		global $wpdb;

		$wpdb->print_error();
	}
}

?>


<?php if ( $update ) : ?>

	<div class="updated inline below-h2">
		<p><?php echo $update; ?></p>
	</div>

<?php endif; ?>

<?php if ( $error ) : ?>

	<div class="error inline below-h2">
		<p><?php echo $error; ?></p>
	</div>

<?php endif; ?>

<?php

function pronamic_ideal_private_key_field( $field, $configuration ) {
	printf(
		'<p><pre class="security-data">%s</pre></p>',
		$configuration->{$field['name']}
	);

	submit_button(
		__( 'Download Private Key', 'pronamic_ideal' ),
		'secondary' , 'download_private_key'
	);
}

function pronamic_ideal_private_certificate_field( $field, $configuration ) {
	$value = $configuration->{$field['name']};

	printf(
		'<p><pre class="security-data">%s</pre></p>',
		$value
	);

	if ( ! empty( $configuration->privateCertificate ) ) {
		$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $configuration->privateCertificate );
		$fingerprint = str_split( $fingerprint, 2 );
		$fingerprint = implode( ':', $fingerprint );

		echo '<dl>';

		echo '<dt>', __( 'SHA Fingerprint', 'pronamic_ideal' ), '</dt>';
		echo '<dd>', $fingerprint, '</dd>';

		$info = openssl_x509_parse( $value );

		if ( $info ) {
			$date_format = __( 'M j, Y @ G:i', 'pronamic_ideal' );

			if ( isset( $info['validFrom_time_t'] ) ) {
				echo '<dt>', __( 'Valid From', 'pronamic_ideal' ), '</dt>';
				echo '<dd>', date_i18n( $date_format, $info['validFrom_time_t'] ), '</dd>';
			}

			if ( isset( $info['validTo_time_t'] ) ) {
				echo '<dt>', __( 'Valid To', 'pronamic_ideal' ), '</dt>';
				echo '<dd>', date_i18n( $date_format, $info['validTo_time_t'] ), '</dd>';
			}
		}

		echo '</dl>';
	}

	submit_button(
		__( 'Download Private Certificate', 'pronamic_ideal' ),
		'secondary' , 'download_private_certificate'
	);
}

?>
<div id="pronamic-ideal-configration-editor">
	
	<?php wp_nonce_field('pronamic_ideal_save_configuration', 'pronamic_ideal_nonce'); ?>
	<input name="pronamic_ideal_configuration_id" value="<?php echo esc_attr( $configuration->getId() ); ?>" type="hidden" />
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="pronamic_ideal_variant_id">
					<?php _e('Variant', 'pronamic_ideal'); ?>
				</label>
			</th>
			<td>
				<?php $variant_id = $configuration->getVariant() == null ? '' : $configuration->getVariant()->getId(); ?>
	                <select id="pronamic_ideal_variant_id" name="pronamic_ideal_variant_id">
	                	<option value=""></option>
	                	<?php foreach ( Pronamic_WordPress_IDeal_ConfigurationsRepository::getProviders() as $provider ) : ?>
						<optgroup label="<?php echo $provider->getName(); ?>">
							<?php foreach ( $provider->getVariants() as $variant ) : ?>
								<option data-ideal-method="<?php echo $variant->getMethod(); ?>" value="<?php echo $variant->getId(); ?>" <?php selected( $variant_id, $variant->getId() ); ?>><?php echo $variant->getName(); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
	                </select>
				</td>
			</tr>
		</table>
	
		<?php foreach ( $sections as $section ) : ?>
	
		<?php
	
		$classes = array();
		if ( isset( $section['methods'] ) ) {
			$classes[] = 'extra-settings';
	
			foreach ( $section['methods'] as $method ) {
				$classes[] = 'method-' . $method;
			}
		}
	
		?>
	
		<div class="<?php echo implode( ' ', $classes ); ?>">
			<h4><?php echo $section['title']; ?></h4>
	
			<table class="form-table">
	
				<?php foreach ( $section['fields'] as $field ) : ?>
	
					<?php
	
					$classes = array();
					if ( isset( $field['methods'] ) ) {
						$classes[] = 'extra-settings';
	
						foreach ( $field['methods'] as $method ) {
							$classes[] = 'method-' . $method;
						}
					}
	
					?>
					<tr class="<?php echo implode( ' ', $classes ); ?>">
						<th scope="col">
							<label for="<?php echo $field['id']; ?>">
								<?php echo $field['title']; ?>
							</label>
						</th>
						<td>
							<?php
	
							$attributes = array();
							$attributes['id']   = $field['id'];
							$attributes['name'] = $field['id'];
	
							$classes = array();
							if ( isset( $field['classes'] ) ) {
								$classes = $field['classes'];
							}
	
							if ( isset( $field['readonly'] ) && $field['readonly'] ) {
								$attributes['readonly'] = 'readonly';
	
								$classes[] = 'readonly';
							}
	
							if ( ! empty( $classes ) ) {
								$attributes['class'] = implode( ' ', $classes );
							}
	
							$value = '';
							if ( isset( $field['meta_key'] ) ) {
								$value = get_post_meta( get_the_ID(), $field['meta_key'], true );
							} elseif ( isset( $field['name'] ) ) {
								$value = $configuration->{$field['name']};
							} elseif( isset( $field['value'] ) ) {
								$value = $field['value'];
							}
	
							switch ( $field['type'] ) {
								case 'text' :
									$attributes['type']  = 'text';
									$attributes['value'] = $value;
	
									printf(
										'<input %s />',
										Pronamic_IDeal_HTML_Helper::array_to_html_attributes( $attributes )
									);
	
									break;
								case 'file' :
									$attributes['type']  = 'file';
	
									printf(
										'<input %s />',
										Pronamic_IDeal_HTML_Helper::array_to_html_attributes( $attributes )
									);
	
									break;
								case 'select' :
									printf(
										'<select %s>%s</select>',
										Pronamic_IDeal_HTML_Helper::array_to_html_attributes( $attributes ),
										Pronamic_IDeal_HTML_Helper::select_options_grouped( $field['options'], $value )
									);
	
									break;
								case 'optgroup' :
									printf( '<fieldset>' );
									printf( '<legend class="screen-reader-text">%s</legend>', $field['title'] );
	
									printf( '<p>' );
	
									foreach ( $field['options'] as $key => $label ) {
										printf(
											'<label>%s %s</label><br />',
											sprintf(
												'<input type="radio" value="%s" name="%s" %s />',
												$key,
												$field['id'],
												checked( $value, $key, false )
											),
											$label
										);
									}
	
									break;
							}
	
							if ( isset( $field['description'] ) ) {
								printf(
									'<span class="description"><br />%s</span>',
									$field['description']
								);
							}
	
							if ( isset( $field['callback'] ) ) {
								$callback = $field['callback'];
	
								$callback( $field, $configuration );
							}
	
							?>
	
						</td>
					</tr>
	
				<?php endforeach; ?>
	
			</table>
		</div>
	
	<?php endforeach; ?>
	
	<?php
	
	submit_button(
		empty( $configuration->id ) ? __( 'Save', 'pronamic_ideal' ) : __( 'Update', 'pronamic_ideal' ),
		'primary',
		'submit'
	);
	
	?>
	
	<div class="extra-settings method-advanced">
		<h4>
			<?php _e( 'Private Key and Certificate Generator', 'pronamic_ideal' ); ?>
		</h4>
	
		<?php
	
		submit_button(
			__( 'Generate', 'pronamic_ideal' ),
			'secundary',
			'generate'
		);
	
		?>
	</div>
	
	<div class="extra-settings method-advanced_v3">
		<h4>
			<?php _e( 'Private Key and Certificate Generator', 'pronamic_ideal' ); ?>
		</h4>
	
		<p>
			<?php _e( 'You have to use the following commands to generate an private key and certificate for iDEAL v3:', 'pronamic_ideal' ); ?>
		</p>
	
		<table class="form-table">
			<tr>
				<th scope="col">
					<label for="pronamic_ideal_openssl_command_key">
						<?php _e( 'Private Key', 'pronamic_ideal' ); ?>
					</label>
				</th>
				<td>
					<?php
	
					$filename = __( 'filename', 'pronamic_ideal' );
	
					$command = sprintf(
						'openssl genrsa -aes128 -out %s.key -passout pass:%s 2048',
						$filename,
						$configuration->privateKeyPassword
					);
	
					?>
					<input id="pronamic_ideal_openssl_command_key" name="pronamic_ideal_openssl_command_key" value="<?php echo esc_attr( $command ); ?>" type="text" class="regular-text code" readonly="readonly" />
				</td>
			</tr>
			<tr>
				<th scope="col">
					<label for="pronamic_ideal_openssl_command_certificate">
						<?php _e( 'Private Certificate', 'pronamic_ideal' ); ?>
					</label>
				</th>
				<td>
					<?php
	
					$command = sprintf(
						'openssl req -x509 -new -key %s.key -passin pass:%s -days %d -out %s.cer',
						$filename,
						$configuration->privateKeyPassword,
						$configuration->numberDaysValid,
						$filename
					);
	
					?>
					<input id="pronamic_ideal_openssl_command_certificate" name="pronamic_ideal_openssl_command_certificate" value="<?php echo esc_attr( $command ); ?>" type="text" class="regular-text code" readonly="readonly" />
				</td>
			</tr>
		</table>
	</div>
</div>