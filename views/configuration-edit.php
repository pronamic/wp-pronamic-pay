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
	$configuration->numberDaysValid = 365;
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

// Request
if ( ! empty( $_POST ) && check_admin_referer( 'pronamic_ideal_save_configuration', 'pronamic_ideal_nonce' ) ) {
	$variantId = filter_input( INPUT_POST, 'pronamic_ideal_variant_id', FILTER_SANITIZE_STRING );
	$variant = Pronamic_WordPress_IDeal_ConfigurationsRepository::getVariantById( $variantId );
	
	$configuration->setVariant( $variant );
	$configuration->setMerchantId( filter_input( INPUT_POST, 'pronamic_ideal_merchant_id', FILTER_SANITIZE_STRING ) );
	$configuration->setSubId( filter_input( INPUT_POST, 'pronamic_ideal_sub_id', FILTER_SANITIZE_STRING ) );
	$configuration->mode = filter_input( INPUT_POST, 'pronamic_ideal_mode', FILTER_SANITIZE_STRING );

	// Basic
	$configuration->hashKey = filter_input( INPUT_POST, 'pronamic_ideal_hash_key', FILTER_SANITIZE_STRING );

	// OmniKassa
	$configuration->keyVersion = filter_input( INPUT_POST, 'pronamic_ideal_key_version', FILTER_SANITIZE_STRING );
	
	// Mollie
	$configuration->molliePartnerId = filter_input( INPUT_POST, 'pronamic_ideal_mollie_partner_id', FILTER_SANITIZE_STRING );
	$configuration->mollieProfileKey = filter_input( INPUT_POST, 'pronamic_ideal_mollie_profile_key', FILTER_SANITIZE_STRING );
	
  // Buckaroo
	$configuration->setMerchantId = filter_input( INPUT_POST, 'pronamic_ideal_merchant_id', FILTER_SANITIZE_STRING );
	$configuration->hashKey = filter_input( INPUT_POST, 'pronamic_ideal_hash_key', FILTER_SANITIZE_STRING );
	
	// TargetPay
	$configuration->targetPayLayoutCode = filter_input( INPUT_POST, 'pronamic_ideal_targetpay_layoutcode', FILTER_SANITIZE_STRING );
	
	// Kassa
	$configuration->pspId = filter_input( INPUT_POST, 'pronamic_ideal_pspid', FILTER_SANITIZE_STRING );
	$configuration->shaInPassPhrase = filter_input( INPUT_POST, 'pronamic_ideal_sha_in_pass_phrase', FILTER_SANITIZE_STRING );
	$configuration->shaOutPassPhrase = filter_input( INPUT_POST, 'pronamic_ideal_sha_out_pass_phrase', FILTER_SANITIZE_STRING );
	
	// Advanced
	if ( $_FILES['pronamic_ideal_private_key']['error'] == UPLOAD_ERR_OK ) {
		$configuration->privateKey = file_get_contents( $_FILES['pronamic_ideal_private_key']['tmp_name'] );
	}

	$configuration->privateKeyPassword = filter_input( INPUT_POST, 'pronamic_ideal_private_key_password', FILTER_SANITIZE_STRING );

	if ( $_FILES['pronamic_ideal_private_certificate']['error'] == UPLOAD_ERR_OK ) {
		$configuration->privateCertificate = file_get_contents( $_FILES['pronamic_ideal_private_certificate']['tmp_name'] );
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
<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Configuration', 'pronamic_ideal' ); ?>
	</h2>

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
	
	$sections = array();

	// Global secetion
	$sections['general'] = array(
		'title'  => __( 'General', 'pronamic_ideal' )
	);

	$fields = array();

	$variant_id = $configuration->getVariant() == null ? '' : $configuration->getVariant()->getId();

	$options = array();

	foreach ( Pronamic_WordPress_IDeal_ConfigurationsRepository::getProviders() as $provider ) {
		$group = array(
			'name'    => $provider->getName(),
			'options' => array()
		);

		foreach ( $provider->getVariants() as $variant ) {
			$group['options'][$variant->getId()] = $variant->getName();
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
					'id'          => 'pronamic_ideal_mode',
					'title'       => __( 'Mode', 'pronamic_ideal' ),
					'type'        => 'optgroup',
					'value'       => $configuration->mode,
					'options'     => array(
						Pronamic_IDeal_IDeal::MODE_LIVE => __( 'Live', 'pronamic_ideal' ),
						Pronamic_IDeal_IDeal::MODE_TEST => __( 'Test', 'pronamic_ideal' ),
					)
				),
				array(
					'id'          => 'pronamic_ideal_merchant_id',
					'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->getMerchantId(),
					'description' => __( 'You receive the merchant ID (also known as: acceptant ID) from your iDEAL provider.', 'pronamic_ideal' ),
					'methods'     => array( 'basic', 'omnikassa', 'advanced', 'advanced_v3' )
				),
				array(
					'id'          => 'pronamic_ideal_sub_id',
					'title'       => __( 'Sub ID', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->getSubId(),
					'description' => sprintf( __( 'You receive the sub ID from your iDEAL provider, the default is: %s.', 'pronamic_ideal' ), 0 ),
					'methods'     => array( 'basic', 'advanced', 'advanced_v3' )
				),
				array(
					'id'          => 'pronamic_ideal_key_version',
					'title'       => __( 'Key Version', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->keyVersion,
					'description' => sprintf( __( 'You can find the key version in the <a href="%s" target="_blank">OmniKassa Download Dashboard</a>.', 'pronamic_ideal' ), 'https://download.omnikassa.rabobank.nl/' ),
					'methods'     => array( 'omnikassa' )
				),
				array(
					'id'          => 'pronamic_ideal_hash_key',
					'title'       => __( 'Hash Key', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->hashKey,
					'description' => __( 'You configure the hash key (also known as: key or secret key) in the iDEAL dashboard of your iDEAL provider.', 'pronamic_ideal' ),
					'methods'     => array( 'basic', 'omnikassa' )
				)
			)
		),
		array(
			'title'   => __( 'Basic', 'pronamic_ideal' ),
			'methods' => array( 'basic' ),
			'fields'  => array(
				array(
						'id'          => 'pronamic_ideal_basic_xml_notification_url',
						'title'       => __( 'XML Notification URL', 'pronamic_ideal' ),
						'type'        => 'text',
						'value'       => add_query_arg( array(
							'gateway'         => 'ideal_basic',
							'xml_notifaction' => 'true'
						), site_url( '/' ) ),
						'methods'     => array( 'basic' ),
						'readonly'    => true
				),
			)
		),
		array(
			'title'   => __( 'Mollie', 'pronamic_ideal' ),
			'methods' => array( 'mollie' ),
			'fields'  => array(
				array(
					'id'          => 'pronamic_ideal_mollie_partner_id',
					'title'       => __( 'Partner ID', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->molliePartnerId,
					'description' => __( 'Mollie.nl accountnummer. Op het gespecificeerde account wordt na succesvolle betaling tegoed bijgeschreven.', 'pronamic_ideal' ),
				),
				 array(
					'id'          => 'pronamic_ideal_mollie_profile_key',
					'title'       => __( 'Profile Key', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->mollieProfileKey,
					'description' => sprintf(
						__( 'Hiermee kunt u een ander websiteprofielen selecteren om uw betaling aan te linken. Gebruik de waarde uit het veld Key uit het profiel overzicht. [<a href="%s" target="_blank">bekijk overzicht van uw profielen</a>].', 'pronamic_ideal' ),
						'https://www.mollie.nl/beheer/account/profielen/'
					)
				)
			)
		),
		    array(
			'title'   => __( 'Buckaroo', 'pronamic_ideal' ),
			'methods' => array( 'buckaroo' ),
			'fields'  => array(
				array(
					'id'          => 'pronamic_ideal_merchant_id',
					'title'       => __( 'Merchant ID', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->getMerchantId(),
					'description' => __( 'Buckaroo.nl Merchant id Nummer. Deze heeft u per mail ontvangen van Buckaroo', 'pronamic_ideal' ),
				),
				 array(
					'id'          => 'pronamic_ideal_hash_key',
					'title'       => __( 'Website Key', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->hashKey,
					'description' => sprintf(
						__( 'Hiermee kunt u een ander websiteprofielen selecteren om uw betaling aan te linken. Gebruik de waarde uit het veld Key uit het profiel overzicht. [<a href="%s" target="_blank">bekijk overzicht van uw profielen</a>].', 'pronamic_ideal' ),
						'https://payment.buckaroo.nl/'
		                              )
				      )
			                   )
		         ),
		array(
			'title'   => __( 'TargetPay', 'pronamic_ideal' ),
			'methods' => array( 'targetpay' ),
			'fields'  => array(
				array(
					'id'          => 'pronamic_ideal_targetpay_layoutcode',
					'title'       => __( 'Layout Code', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->targetPayLayoutCode,
					'description' => __( 'De layoutcode waarop de betaling geboekt moet worden. Zie subaccounts.', 'pronamic_ideal' ),
				)
			)
		),
		array(
			'title'   => __( 'Internetkassa', 'pronamic_ideal' ),
			'methods' => array( 'easy', 'internetkassa' ),
			'fields'  => array(
				array(
					'id'          => 'pronamic_ideal_pspid',
					'title'       => __( 'PSPID', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->pspId,
					'description' => sprintf(
							__( 'If you use the ABN AMRO - IDEAL Easy variant you can use <code>%s</code>.', 'pronamic_ideal' ),
							'TESTiDEALEASY'
					),
					'methods'     => array( 'easy', 'internetkassa' )
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
					'id'          => 'pronamic_ideal_sha_in_pass_phrase',
					'title'       => __( 'SHA-IN Pass phrase', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->shaInPassPhrase,
					'description' => __( 'You configure the SHA-IN Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Data and origin verification) of your iDEAL provider.', 'pronamic_ideal' ),
					'methods'     => array( 'internetkassa' )
				),
				array(
					'id'          => 'pronamic_ideal_sha_out_pass_phrase',
					'title'       => __( 'SHA-OUT Pass phrase', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->shaOutPassPhrase,
					'description' => __( 'You configure the SHA-OUT Pass phrase in the iDEAL dashboard (Configuration &raquo; Technical information &raquo; Transaction feedback) of your iDEAL provider.', 'pronamic_ideal' ),
					'methods'     => array( 'internetkassa' )
				)
			)
		),
		array(
			'title'   => __( 'Advanced', 'pronamic_ideal' ),
			'methods' => array( 'advanced', 'advanced_v3' ),
			'fields'  => array(
				array(
					'id'          => 'pronamic_ideal_private_key_password',
					'title'       => __( 'Private Key Password', 'pronamic_ideal' ),
					'type'        => 'text',
					'value'       => $configuration->privateKeyPassword
				),
				array(
					'id'          => 'pronamic_ideal_private_key',
					'title'       => __( 'Private Key', 'pronamic_ideal' ),
					'type'        => 'file',
					'value'       => $configuration->privateKey,
					'callback'    => 'pronamic_ideal_private_key_field'
				),
				array(
					'id'          => 'pronamic_ideal_private_certificate',
					'title'       => __( 'Private Certificate', 'pronamic_ideal' ),
					'type'        => 'file',
					'value'       => $configuration->privateCertificate,
					'callback'    => 'pronamic_ideal_private_certificate_field'
				)
			)
		)
	);

	function pronamic_ideal_private_key_field( $field, $configuration ) {
		printf(
			'<p><pre class="security-data">%s</pre></p>',
			$field['value']
		);

		submit_button(
			__( 'Download Private Key', 'pronamic_ideal' ),
			'secondary' , 'download_private_key'
		);
	}

	function pronamic_ideal_private_certificate_field( $field, $configuration ) {
		printf(
			'<p><pre class="security-data">%s</pre></p>',
			$field['value']
		);

		if ( ! empty( $configuration->privateCertificate ) ) {
			$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $configuration->privateCertificate );
			$fingerprint = str_split( $fingerprint, 2 );
			$fingerprint = implode( ':', $fingerprint );

			echo '<dl>';

			echo '<dt>', __( 'SHA Fingerprint', 'pronamic_ideal' ), '</dt>';
			echo '<dd>', $fingerprint, '</dd>';

			$info = openssl_x509_parse( $field['value'] );
			
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
	
	<form id="pronamic-ideal-configration-editor" enctype="multipart/form-data" action="" method="post">
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
				<h3><?php echo $section['title']; ?></h3>
	
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
		
								if ( isset( $field['readonly'] ) && $field['readonly'] ) {
									$attributes['readonly'] = 'readonly';
		
									$classes[] = 'regular-text';
									$classes[] = 'readonly';
								}
								
								if ( ! empty( $classes ) ) {
									$attributes['class'] = implode( ' ', $classes );
								}						
								
								switch ( $field['type'] ) {
									case 'text' :
										$attributes['type']  = 'text';
										$attributes['value'] = $field['value'];
		
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
											Pronamic_IDeal_HTML_Helper::select_options_grouped( $field['options'], $field['value'] )
										);
		
										break;
									case 'optgroup' :
										printf( '<fieldset>' );
										printf( '<legend class="screen-reader-text">%s</legend>', __( 'Mode', 'pronamic_ideal' ) );
		
										printf( '<p>' );
										
										foreach ( $field['options'] as $value => $label ) {
											printf( 
												'<label>%s %s</label><br />',
												sprintf( 
													'<input type="radio" value="%s" name="%s" %s />',
													$value,
													$field['id'],
													checked( $field['value'], $value, false )
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

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_generate_private_key_password">
							<?php _e( 'Private Key Password', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_generate_private_key_password" name="pronamic_ideal_generate_private_key_password" value="<?php echo $configuration->privateKeyPassword; ?>" type="text" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_days">
							<?php _e( 'Number Days Valid', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_number_days_valid" name="pronamic_ideal_number_days_valid" value="<?php echo $configuration->numberDaysValid; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'specify the length of time for which the generated certificate will be valid, in days. ', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_country_name">
							<?php _e( 'Country', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_country" name="pronamic_ideal_country" value="<?php echo $configuration->country; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( '2 letter code [NL]', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_state_or_province">
							<?php _e( 'State or Province', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_state_or_province" name="pronamic_ideal_state_or_province" value="<?php echo $configuration->stateOrProvince; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'full name [Friesland]', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_locality">
							<?php _e( 'Locality', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_locality" name="pronamic_ideal_locality" value="<?php echo $configuration->locality; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'eg, city', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_organization">
							<?php _e( 'Organization', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_organization" name="pronamic_ideal_organization" value="<?php echo $configuration->organization; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'eg, company [Pronamic]', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_organization_unit">
							<?php _e( 'Organization Unit', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_organization_unit" name="pronamic_ideal_organization_unit" value="<?php echo $configuration->organizationUnit; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'eg, section', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_common_name">
							<?php _e( 'Common Name', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_common_name" name="pronamic_ideal_common_name" value="<?php echo $configuration->commonName; ?>" type="text" />

						<span class="description">
							<br />
							<?php _e( 'eg, YOUR name', 'pronamic_ideal' ); ?>
							<?php _e( 'Do you have an iDEAL subscription with Rabobank or ING Bank, please fill in the domainname of your website.', 'pronamic_ideal' ); ?>
							<?php _e( 'Do you have an iDEAL subscription with ABN AMRO, please fill in "ideal_<strong>company</strong>", where "company" is your company name (as specified in the request for the subscription). The value must not exceed 25 characters.', 'pronamic_ideal' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_email_address">
							<?php _e( 'Email Address', 'pronamic_ideal' ); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_email_address" name="pronamic_ideal_email_address" value="<?php echo $configuration->eMailAddress; ?>" type="text" />
					</td>
				</tr>
			</table>

			<?php

			submit_button(
				__( 'Generate', 'pronamic_ideal' ),
				'secundary', 
				'generate'
			);

			?>
		</div>
	</form>
</div>