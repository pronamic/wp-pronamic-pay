<?php

/**
 * Title: WordPress admin gateway post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.8.0
 * @since ?
 */
class Pronamic_WP_Pay_Admin_GatewayPostType {
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_gateway';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin gateway post type object
	 */
	public function __construct( $admin ) {
		$this->admin = $admin;

		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'post_edit_form_tag', array( $this, 'post_edit_form_tag' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_post' ) );

		add_action( 'display_post_states', array( $this, 'display_post_states' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	public function edit_columns( $columns ) {
		$columns = array(
			'cb'                           => '<input type="checkbox" />',
			'title'                        => __( 'Title', 'pronamic_ideal' ),
			'pronamic_gateway_variant'     => __( 'Variant', 'pronamic_ideal' ),
			'pronamic_gateway_id'          => __( 'ID', 'pronamic_ideal' ),
			// 'pronamic_gateway_secret'      => __( 'Secret', 'pronamic_ideal' ),
			'pronamic_gateway_dashboard'   => __( 'Dashboard', 'pronamic_ideal' ),
			'date'                         => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	function custom_columns( $column, $post_id ) {
		$id = get_post_meta( $post_id, '_pronamic_gateway_id', true );

		$integration = $this->admin->plugin->gateway_integrations->get_integration( $id );

		switch ( $column ) {
			case 'pronamic_gateway_variant':
				if ( $integration ) {
					echo esc_html( $integration->get_name() );
				} else {
					echo esc_html( $id );
				}

				break;
			case 'pronamic_gateway_id':
				$data = array_filter( array(
					get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_omnikassa_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_buckaroo_website_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_mollie_partner_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_multisafepay_account_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_pay_nl_service_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_paydutch_username', true ),
					get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_targetpay_layoutcode', true ),
					get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_ogone_user_id', true ),
				) );

				echo esc_html( implode( ' ', $data ) );

				break;
			case 'pronamic_gateway_secret':
				$data = array_filter( array(
					get_post_meta( $post_id, '_pronamic_gateway_ideal_basic_hash_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_omnikassa_secret_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_buckaroo_secret_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_icepay_secret_code', true ),
					get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_secret', true ),
					get_post_meta( $post_id, '_pronamic_gateway_ogone_password', true ),
				) );

				echo esc_html( implode( ' ', $data ) );

				break;
			case 'pronamic_gateway_dashboard':
				if ( $integration ) {
					$urls = $integration->get_dashboard_url();

					// Output
					$content = array();

					foreach ( $urls as $name => $url ) {
						if ( empty( $name ) ) {
							$name = __( 'Dashboard', 'pronamic_ideal' );
						}

						$content[] = sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $url ),
							esc_html( ucfirst( $name ) )
						);
					}

					echo implode( ' | ', $content ); //xss ok
				}

				break;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Display post states
	 *
	 * @param $post_stated array
	 * @param $post WP_Post
	 */
	public function display_post_states( $post_states, $post ) {
		if ( self::POST_TYPE !== get_post_type( $post ) ) {
			return $post_states;
		}

		if ( intval( get_option( 'pronamic_pay_config_id' ) ) === $post->ID ) {
			$post_states['pronamic_pay_config_default'] = __( 'Default', 'pronamic_ideal' );
		}

		return $post_states;
	}

	//////////////////////////////////////////////////

	/**
	 * Post edit form tag
	 *
	 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/edit-form-advanced.php#L299
	 * @see https://github.com/WordPress/WordPress/blob/3.5.2/wp-admin/edit-form-advanced.php#L299
	 *
	 * @param WP_Post $post (only available @since 3.5.2)
	 */
	public function post_edit_form_tag( $post ) {
		if ( empty( $post ) ) {
			global $post;
		}

		if ( $post ) {
			if ( self::POST_TYPE === $post->post_type ) {
				echo ' enctype="multipart/form-data"';
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type ) {
		if ( self::POST_TYPE === $post_type ) {
			add_meta_box(
				'pronamic_gateway_config',
				__( 'Configuration', 'pronamic_ideal' ),
				array( $this, 'meta_box_config' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_gateway_test',
				__( 'Test', 'pronamic_ideal' ),
				array( $this, 'meta_box_test' ),
				$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_config( $post ) {
		wp_nonce_field( 'pronamic_pay_save_gateway', 'pronamic_pay_nonce' );

		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-gateway-config.php';
	}

	/**
	 * Pronamic Pay gateway test meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_test( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-gateway-test.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe set the default gateway.
	 *
	 * @param int $post_id
	 */
	private function maybe_set_default_gateway( $post_id ) {
		// Don't set the default gateway if the post is not published.
		if ( 'publish' !== get_post_status ( $post_id ) ) {
			return;
		}

		// Don't set the default gateway if there is already a published gateway set.
		if ( 'publish' === get_post_status( get_option( 'pronamic_pay_config_id' ) ) ) {
			return;
		}

		update_option( 'pronamic_pay_config_id', $post_id );
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post( $post_id ) {
		// Nonce
		if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			return $post_id;
		}

		check_admin_referer( 'pronamic_pay_save_gateway', 'pronamic_pay_nonce' );

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		/* OK, its safe for us to save the data now. */
		$this->maybe_set_default_gateway( $post_id );

		$fields = $this->admin->gateway_settings->get_fields();

		$definition = array(
			// General
			'_pronamic_gateway_id' => FILTER_SANITIZE_STRING,
		);

		foreach ( $fields as $field ) {
			if ( isset( $field['meta_key'], $field['filter'] ) ) {
				$name   = $field['meta_key'];
				$filter = $field['filter'];

				$definition[ $name ] = $filter;
			}
		}

		$data = filter_input_array( INPUT_POST, $definition );

		// Files
		$files = array(
			'_pronamic_gateway_ideal_private_key_file'         => '_pronamic_gateway_ideal_private_key',
			'_pronamic_gateway_ideal_private_certificate_file' => '_pronamic_gateway_ideal_private_certificate',
		);

		foreach ( $files as $name => $meta_key ) {
			if ( isset( $_FILES[ $name ] ) && UPLOAD_ERR_OK === $_FILES[ $name ]['error'] ) {
				$value = file_get_contents( $_FILES[ $name ]['tmp_name'] );

				$data[ $meta_key ] = $value;
			}
		}

		foreach( $fields as $field ) {
			if ( isset( $field['meta_key'], $field['default'], $data[$field['meta_key']] ) && empty( $data[$field['meta_key']] ) ) {
				$default = $field['default'];

				$data[$field['meta_key']] = $default;

				if ( is_array( $default ) ) {
					$data[$field['meta_key']] = $default( $field );
				}
			}
		}

		// Generate private key and certificate
		if ( isset( $data['_pronamic_gateway_ideal_private_key_password'] ) && ! empty( $data['_pronamic_gateway_ideal_private_key_password'] ) ) {
			$cipher_methods = openssl_get_cipher_methods();

			$cipher_method = 'aes-128-cbc';

			if ( array_search( $cipher_method, $cipher_methods ) ) {
				$args = array (
					'digest_alg'             => 'SHA256',
					'private_key_bits'       => 2048,
					'private_key_type'       => OPENSSL_KEYTYPE_RSA,
					'encrypt_key'            => true,
					'encrypt_key_cipher'     => OPENSSL_CIPHER_AES_128_CBC,
					'subjectKeyIdentifier'   => 'hash',
					'authorityKeyIdentifier' => 'keyid:always,issuer:always',
					'basicConstraints'       => 'CA:true',
				);

				// Private key
				if ( empty( $data['_pronamic_gateway_ideal_private_key'] ) ) {
					$pkey = openssl_pkey_new( $args );

					openssl_pkey_export( $pkey, $private_key, $data[ '_pronamic_gateway_ideal_private_key_password' ], $args );

					$data[ '_pronamic_gateway_ideal_private_key' ] = $private_key;
				} else {
					$pkey = $data['_pronamic_gateway_ideal_private_key'];
				}

				// Certificate
				if ( empty( $data['_pronamic_gateway_ideal_private_certificate'] ) && ! empty( $pkey ) ) {
					$distinguished_name = array(
						'countryName'            => $data['_pronamic_gateway_country'],
						'stateOrProvinceName'    => $data['_pronamic_gateway_state_or_province'],
						'localityName'           => $data['_pronamic_gateway_locality'],
						'organizationName'       => $data['_pronamic_gateway_organization'],
						'organizationalUnitName' => $data['_pronamic_gateway_organization_unit'],
						'commonName'             => $data['_pronamic_gateway_organization'],
						'emailAddress'           => $data['_pronamic_gateway_email'],
					);

					// If distinguished_name does not contain empty elements, create the certificate
					if ( ! in_array( '', $distinguished_name, true ) ) {
						$csr = openssl_csr_new( $distinguished_name, $pkey );

						$eargs = array( 'authorityKeyIdentifier' => 'authorityKeyIdentifier=keyid:always,issuer:always' );
						$cert = openssl_csr_sign( $csr, null, $pkey, $data['_pronamic_gateway_number_days_valid'], $args, time() );

						openssl_x509_export( $cert, $certificate );

						$data['_pronamic_gateway_ideal_private_certificate'] = $certificate;
					}
				}
			}
		}

		// Update post meta data
		pronamic_pay_update_post_meta_data( $post_id, $data );

		// Transient
		delete_transient( 'pronamic_pay_issuers_' . $post_id );
	}
}
