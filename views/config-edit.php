<?php

// Sections
$variant_id = get_post_meta( get_the_ID(), '_pronamic_gateway_id', true );

$options = array();

global $pronamic_pay_providers;

bind_providers_and_gateways();

foreach ( $pronamic_pay_providers as $provider ) {
	$group = array(
		'name'    => $provider['name'],
		'options' => array(),
	);

	if ( isset( $provider['gateways'] ) ) {
		foreach ( $provider['gateways'] as $id => $gateway ) {
			$group['options'][$id] = $gateway['name'];
		}
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
				),
			),
		),
	),
	array(
		'title'   => __( 'iDEAL', 'pronamic_ideal' ),
		'methods' => array( 'ideal_basic', 'ideal_advanced', 'ideal_advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => __( 'You receive the merchant ID (also known as: acceptant ID) from your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'ideal_basic', 'ideal_advanced', 'ideal_advanced_v3' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_sub_id',
				'name'        => 'subId',
				'id'          => 'pronamic_ideal_sub_id',
				'title'       => __( 'Sub ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'small-text', 'code' ),
				'description' => sprintf( __( 'You receive the sub ID from your iDEAL provider, the default is: %s.', 'pronamic_ideal' ), 0 ),
				'methods'     => array( 'ideal_basic', 'ideal_advanced', 'ideal_advanced_v3' ),
			),
		),
	),
	array(
		'title'   => __( 'Basic', 'pronamic_ideal' ),
		'methods' => array( 'ideal_basic' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_hash_key',
				'title'       => __( 'Hash Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the hash key (also known as: key or secret key) in the iDEAL dashboard of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'ideal_basic' ),
			),
			array(
				'title'       => __( 'XML Notification URL', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'value'       => add_query_arg( array(
					'gateway'         => 'ideal_basic',
					'xml_notifaction' => 'true'
				), site_url( '/' ) ),
				'methods'     => array( 'ideal_basic' ),
				'readonly'    => true,
			),
		),
	),
	array(
		'title'   => __( 'PayDutch', 'pronamic_ideal' ),
		'methods' => array( 'paydutch' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_paydutch_username',
				'title'       => __( 'Username', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_paydutch_password',
				'title'       => __( 'Password', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
			),
		),
	),
	array(
		'title'   => __( 'Mollie', 'pronamic_ideal' ),
		'methods' => array( 'mollie', 'mollie_ideal' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_mollie_partner_id',
				'title'       => __( 'Partner ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => __( 'Mollie.nl accountnummer. Op het gespecificeerde account wordt na succesvolle betaling tegoed bijgeschreven.', 'pronamic_ideal' ),
				'methods'     => array( 'mollie_ideal' ),
			),
			 array(
				'meta_key'    => '_pronamic_gateway_mollie_profile_key',
				'title'       => __( 'Profile Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf(
					__( 'Hiermee kunt u een ander websiteprofielen selecteren om uw betaling aan te linken. Gebruik de waarde uit het veld Key uit het profiel overzicht. [<a href="%s" target="_blank">bekijk overzicht van uw profielen</a>].', 'pronamic_ideal' ),
					'https://www.mollie.nl/beheer/account/profielen/'
				),
				'methods'     => array( 'mollie_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_mollie_api_key',
				'title'       => _x( 'API Key', 'mollie', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'methods'     => array( 'mollie' ),
			),
			array(
				'title'       => __( 'Webhook', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'large-text', 'code' ),
				'value'       => add_query_arg( 'mollie_webhook', '', home_url( '/' ) ),
				'readonly'    => true,
				'methods'     => array( 'mollie' ),
			),
		),
	),
	array(
		'title'   => __( 'OmniKassa', 'pronamic_ideal' ),
		'methods' => array( 'omnikassa' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_secret_key',
				'title'       => __( 'Secret Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'large-text', 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_omnikassa_key_version',
				'title'       => __( 'Key Version', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'small-text', 'code' ),
				'description' => sprintf( __( 'You can find the key version in the <a href="%s" target="_blank">OmniKassa Download Dashboard</a>.', 'pronamic_ideal' ), 'https://download.omnikassa.rabobank.nl/' ),
			),
		),
	),
	array(
		'title'   => __( 'Buckaroo', 'pronamic_ideal' ),
		'methods' => array( 'buckaroo' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_buckaroo_website_key',
				'title'       => __( 'Website Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf( __( 'You can find your Buckaroo website keys in the <a href="%s" target="_blank">Buckaroo Payment Plaza</a> under "Profile" » "Website".', 'pronamic_ideal' ), 'https://payment.buckaroo.nl/' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_buckaroo_secret_key',
				'title'       => __( 'Secret Key', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf( __( 'You can find your Buckaroo secret key in the <a href="%s" target="_blank">Buckaroo Payment Plaza</a> under "Configuration" » "Secret Key for Digital Signature".', 'pronamic_ideal' ), 'https://payment.buckaroo.nl/' ),
			),
		),
	),
	array(
		'title'   => __( 'ICEPAY', 'pronamic_ideal' ),
		'methods' => array( 'icepay' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_icepay_merchant_id',
				'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => sprintf(
					__( 'You can find your Merchant ID on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
					__( 'https://www.icepay.com/EN/Login', 'pronamic_ideal' ),
					__( 'https://www.icepay.com/Merchant/EN/Websites', 'pronamic_ideal' )
				),
			),
			array(
				'meta_key'    => '_pronamic_gateway_icepay_secret_code',
				'title'       => __( 'Secret Code', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf(
					__( 'You can find your Secret Code on your <a href="%s" target="_blank">ICEPAY account page</a> under <a href="%s" target="_blank">My websites</a>.', 'pronamic_ideal' ),
					__( 'https://www.icepay.com/EN/Login', 'pronamic_ideal' ),
					__( 'https://www.icepay.com/Merchant/EN/Websites', 'pronamic_ideal' )
				),
			),
			array(
				'title'       => __( 'Thank you page URL', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'value'       => home_url( '/' ),
				'readonly'    => true,
			),
			array(
				'title'       => __( 'Error page URL', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'value'       => home_url( '/' ),
				'readonly'    => true,
			),
			array(
				'title'       => __( 'Postback URL', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'value'       => home_url( '/' ),
				'readonly'    => true,
			)
		)
	),
	array(
		'title'   => __( 'Sisow', 'pronamic_ideal' ),
		'methods' => array( 'sisow' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_sisow_merchant_id',
				'title'       => _x( 'Merchant ID', 'sisow', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf(
					__( 'You can find your Merchant ID on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
					'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
					'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
				),
			),
			 array(
				'meta_key'    => '_pronamic_gateway_sisow_merchant_key',
				'title'       => _x( 'Merchant Key', 'sisow', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => sprintf(
					__( 'You can find your Merchant Key on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
					'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
					'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
				),
			),
		),
	),
	array(
		'title'   => __( 'TargetPay', 'pronamic_ideal' ),
		'methods' => array( 'targetpay' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_targetpay_layoutcode',
				'title'       => __( 'Layout Code', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'De layoutcode waarop de betaling geboekt moet worden. Zie subaccounts.', 'pronamic_ideal' ),
			),
		),
	),
	array(
		'title'   => __( 'Ogone', 'pronamic_ideal' ),
		'methods' => array( 'ogone_orderstandard_easy', 'ogone_orderstandard', 'ogone_directlink' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ogone_psp_id',
				'title'       => __( 'PSPID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
				'description' => sprintf(
					__( 'If you use the ABN AMRO - IDEAL Easy variant you can use <code>%s</code>.', 'pronamic_ideal' ),
					'TESTiDEALEASY'
				),
				'methods'     => array( 'ogone_orderstandard_easy', 'ogone_orderstandard', 'ogone_directlink' ),
			),
			array(
				'title'       => __( 'Character encoding', 'pronamic_ideal' ),
				'type'        => 'text',
				'value'       => get_bloginfo( 'charset' ),
				'methods'     => array( 'ogone_orderstandard' ),
				'readonly'    => true,
			),
			 array(
				'meta_key'    => '_pronamic_gateway_ogone_hash_algorithm',
				'title'       => __( 'Hash algorithm', 'pronamic_ideal' ),
				'type'        => 'optgroup',
			 	'options'     => array(
			 		Pronamic_Pay_Gateways_Ogone_HashAlgorithms::SHA_1   => __( 'SHA-1', 'pronamic_ideal' ),
			 		Pronamic_Pay_Gateways_Ogone_HashAlgorithms::SHA_256 => __( 'SHA-256', 'pronamic_ideal' ),
			 		Pronamic_Pay_Gateways_Ogone_HashAlgorithms::SHA_512 => __( 'SHA-512', 'pronamic_ideal' )
			 	),
				'methods'     => array( 'ogone_orderstandard', 'ogone_directlink' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_sha_in_pass_phrase',
				'title'       => __( 'SHA-IN Pass phrase', 'pronamic_ideal' ),
				'type'        => 'password',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the SHA-IN Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Data and origin verification) of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'ogone_orderstandard' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_sha_out_pass_phrase',
				'title'       => __( 'SHA-OUT Pass phrase', 'pronamic_ideal' ),
				'type'        => 'password',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the SHA-OUT Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Transaction feedback) of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'ogone_orderstandard', 'ogone_directlink' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_user_id',
				'title'       => __( 'User ID', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
				'methods'     => array( 'ogone_directlink' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_password',
				'title'       => __( 'Password', 'pronamic_ideal' ),
				'type'        => 'password',
				'classes'     => array( 'regular-text', 'code' ),
				'methods'     => array( 'ogone_directlink' ),
			),
		),
	),
	array(
		'title'   => __( 'Ogone DirectLink', 'pronamic_ideal' ),
		'methods' => array( 'ogone_directlink' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ogone_directlink_sha_in_pass_phrase',
				'title'       => __( 'SHA-IN Pass phrase', 'pronamic_ideal' ),
				'type'        => 'password',
				'classes'     => array( 'regular-text', 'code' ),
				'description' => __( 'You configure the SHA-IN Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Data and origin verification) of your iDEAL provider.', 'pronamic_ideal' ),
				'methods'     => array( 'ogone_directlink' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ogone_3d_secure_enabled',
				'title'       => __( '3-D Secure', 'pronamic_ideal' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable 3-D Secure protocol', 'pronamic_ideal' ),
				'methods'     => array( 'ogone_directlink' ),
			),
		),
	),
	array(
		'title'   => __( 'Qantani', 'pronamic_ideal' ),
		'methods' => array( 'qantani' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_id',
				'title'       => _x( 'Merchant ID', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_key',
				'title'       => _x( 'Merchant Key', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_qantani_merchant_secret',
				'title'       => _x( 'Merchant Secret', 'qantani', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
			),
		),
	),
	array(
		'title'   => __( 'iDEAL Advanced', 'pronamic_ideal' ),
		'methods' => array( 'ideal_advanced', 'ideal_advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_key_password',
				'title'       => __( 'Private Key Password', 'pronamic_ideal' ),
				'type'        => 'text',
				'classes'     => array( 'regular-text', 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_key',
				'title'       => __( 'Private Key', 'pronamic_ideal' ),
				'type'        => 'textarea',
				'callback'    => 'pronamic_ideal_private_key_field',
				'classes'     => array( 'code' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_ideal_private_certificate',
				'title'       => __( 'Private Certificate', 'pronamic_ideal' ),
				'type'        => 'textarea',
				'callback'    => 'pronamic_ideal_private_certificate_field',
				'classes'     => array( 'code' ),
			),
		),
	),
	array(
		'title'   => __( 'Private Key and Certificate', 'pronamic_ideal' ),
		'methods' => array( 'ideal_advanced', 'ideal_advanced_v3' ),
		'fields'  => array(
			array(
				'meta_key'    => '_pronamic_gateway_number_days_valid',
				'title'       => __( 'Number Days Valid', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'specify the length of time for which the generated certificate will be valid, in days.', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_country',
				'title'       => __( 'Country', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( '2 letter code [NL]', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_state_or_province',
				'title'       => __( 'State or Province', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'full name [Friesland]', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_locality',
				'title'       => __( 'Locality', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, city', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_organization',
				'title'       => __( 'Organization', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, company [Pronamic]', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_organization_unit',
				'title'       => __( 'Organization Unit', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => __( 'eg, section', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_common_name',
				'title'       => __( 'Common Name', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' =>
					__( 'eg, YOUR name', 'pronamic_ideal' ) . '<br />' .
					__( 'Do you have an iDEAL subscription with Rabobank or ING Bank, please fill in the domainname of your website.', 'pronamic_ideal' ) . '<br />' .
					__( 'Do you have an iDEAL subscription with ABN AMRO, please fill in "ideal_<strong>company</strong>", where "company" is your company name (as specified in the request for the subscription). The value must not exceed 25 characters.', 'pronamic_ideal' ),
			),
			array(
				'meta_key'    => '_pronamic_gateway_email',
				'title'       => __( 'Email Address', 'pronamic_ideal' ),
				'type'        => 'text',
			),
		),
	),
);


function pronamic_ideal_private_key_field( $field ) {
	echo '<div>';

	submit_button(
		__( 'Download Private Key', 'pronamic_ideal' ),
		'secondary' , 'download_private_key',
		false
	);
	
	echo ' ';

	echo '<input type="file" name="_pronamic_gateway_ideal_private_key_file" />';

	echo '</div>';
}

function pronamic_ideal_private_certificate_field( $field ) {
	$certificate = get_post_meta( get_the_ID(), '_pronamic_gateway_ideal_private_certificate', true );

	if ( ! empty( $certificate ) ) {
		$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $certificate );
		$fingerprint = str_split( $fingerprint, 2 );
		$fingerprint = implode( ':', $fingerprint );

		echo '<dl>';

		echo '<dt>', __( 'SHA Fingerprint', 'pronamic_ideal' ), '</dt>';
		echo '<dd>', $fingerprint, '</dd>';

		$info = openssl_x509_parse( $certificate );

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

	echo '<div>';
	
	submit_button(
		__( 'Download Private Certificate', 'pronamic_ideal' ),
		'secondary' , 'download_private_certificate',
		false
	);
	
	echo ' ';
	
	echo '<input type="file" name="_pronamic_gateway_ideal_private_certificate_file" />';
	
	echo '</div>';
}

?>
<div id="pronamic-pay-gateway-config-editor">
	<?php wp_nonce_field( 'pronamic_pay_save_gateway', 'pronamic_pay_nonce' ); ?>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="pronamic_gateway_id">
					<?php _e('Variant', 'pronamic_ideal'); ?>
				</label>
			</th>
			<td>
                <select id="pronamic_gateway_id" name="_pronamic_gateway_id">
                	<option value=""></option>

                	<?php foreach ( $pronamic_pay_providers as $provider ) : ?>
						<optgroup label="<?php echo $provider['name']; ?>">
							<?php foreach ( $provider['gateways']  as $id => $gateway ) : ?>
								<option data-ideal-method="<?php echo $gateway['gateway']; ?>" value="<?php echo $id; ?>" <?php selected( $variant_id, $id ); ?>><?php echo $gateway['name']; ?></option>
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

					if ( isset( $field['id'] ) ) {
						$id = $field['id'];
					} elseif( isset( $field['meta_key'] ) ) {
						$id = $field['meta_key'];
					} else {
						$id = uniqid();
					}
	
					?>
					<tr class="<?php echo implode( ' ', $classes ); ?>">
						<th scope="col">
							<label for="<?php echo esc_attr( $id ); ?>">
								<?php echo $field['title']; ?>
							</label>
						</th>
						<td>
							<?php
	
							$attributes = array();
							$attributes['id']   = $id;
							$attributes['name'] = $id;
	
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
								$attributes['name'] = $field['meta_key'];
	
								$value = get_post_meta( get_the_ID(), $field['meta_key'], true );
							} elseif( isset( $field['value'] ) ) {
								$value = $field['value'];
							}
	
							switch ( $field['type'] ) {
								case 'text' :
								case 'password' :
									$attributes['type']  = $field['type'];
									$attributes['value'] = $value;
	
									printf(
										'<input %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes )
									);

									break;
								case 'checkbox' :
									$attributes['type']  = $field['type'];
									$attributes['value'] = '1';
	
									printf(
										'<input %s %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										checked( $value, true, false )
									);
									
									printf( ' ' );
									
									printf(
										'<label for="%s">%s</label>',
										$attributes['id'],
										$field['label']
									);
	
									break;
								case 'textarea' :
									$attributes['rows'] = 4;
									$attributes['cols'] = 65;
	
									printf(
										'<textarea %s />%s</textarea>',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										esc_textarea( $value )
									);
	
									break;
								case 'file' :
									$attributes['type']  = 'file';
	
									printf(
										'<input %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes )
									);
	
									break;
								case 'select' :
									printf(
										'<select %s>%s</select>',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										Pronamic_WP_HTML_Helper::select_options_grouped( $field['options'], $value )
									);
	
									break;
								case 'optgroup' :
									printf( '<fieldset>' );
									printf( '<legend class="screen-reader-text">%s</legend>', $field['title'] );

									foreach ( $field['options'] as $key => $label ) {
										printf(
											'<label>%s %s</label><br />',
											sprintf(
												'<input type="radio" value="%s" name="%s" %s />',
												$key,
												$attributes['name'],
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
	
								$callback( $field );
							}
	
							?>
	
						</td>
					</tr>
	
				<?php endforeach; ?>
	
			</table>
		</div>

	<?php endforeach; ?>
	
	<div class="extra-settings method-ideal_advanced_v3">
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
					
					$private_key_password = get_post_meta( get_the_ID(), '_pronamic_gateway_ideal_private_key_password', true );
					$number_days_valid    = get_post_meta( get_the_ID(), '_pronamic_gateway_number_days_valid', true );

					$filename = __( 'filename', 'pronamic_ideal' );
	
					$command = sprintf(
						'openssl genrsa -aes128 -out %s.key -passout pass:%s 2048',
						$filename,
						$private_key_password
					);
	
					?>
					<input id="pronamic_ideal_openssl_command_key" name="pronamic_ideal_openssl_command_key" value="<?php echo esc_attr( $command ); ?>" type="text" class="large-text code" readonly="readonly" />
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

					// @see http://www.openssl.org/docs/apps/req.html
					$subj_args = array(
						'C'             => get_post_meta( get_the_ID(), '_pronamic_gateway_country', true ),
						'ST'            => get_post_meta( get_the_ID(), '_pronamic_gateway_state_or_province', true ),
						'L'             => get_post_meta( get_the_ID(), '_pronamic_gateway_locality', true ),
						'O'             => get_post_meta( get_the_ID(), '_pronamic_gateway_organization', true ),
						'OU'            => get_post_meta( get_the_ID(), '_pronamic_gateway_organization_unit', true ),
						'CN'            => get_post_meta( get_the_ID(), '_pronamic_gateway_common_name', true ),
						'emailAddress'  => get_post_meta( get_the_ID(), '_pronamic_gateway_email', true ),
					);

					$subj_args = array_filter( $subj_args );

					$subj = '';
					foreach ( $subj_args as $type => $value ) {
						$subj .= '/' . $type . '=' . '"' . addslashes( $value ) . '"';
					}

					$command = trim( sprintf(
						'openssl req -x509 -new -key %s.key -passin pass:%s -days %d -out %s.cer %s',
						$filename,
						$private_key_password,
						$number_days_valid,
						$filename,
						empty( $subj ) ? '' : '-subj ' . $subj
					) );

					?>
					<input id="pronamic_ideal_openssl_command_certificate" name="pronamic_ideal_openssl_command_certificate" value="<?php echo esc_attr( $command ); ?>" type="text" class="large-text code" readonly="readonly" />
				</td>
			</tr>
		</table>
	</div>
</div>