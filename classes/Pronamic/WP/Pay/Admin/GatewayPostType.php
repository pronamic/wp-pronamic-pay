<?php

/**
 * Title: WordPress admin gateway post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_Admin_GatewayPostType {
	public function __construct() {
		add_filter( 'manage_edit-pronamic_gateway_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_pronamic_gateway_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'post_edit_form_tag', array( $this, 'post_edit_form_tag' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_post' ) );
	}

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
		global $post;
		global $pronamic_pay_gateways;

		switch ( $column ) {
			case 'pronamic_gateway_variant':
				$id = get_post_meta( $post_id, '_pronamic_gateway_id', true );

				if ( isset( $pronamic_pay_gateways[ $id ] ) ) {
					echo $pronamic_pay_gateways[ $id ]['name'];
				} else {
					echo $id;
				}

				break;
			case 'pronamic_gateway_id':
				$data = array_filter( array(
					get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_omnikassa_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_buckaroo_website_key', true ),
					get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_mollie_partner_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_paydutch_username', true ),
					get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_targetpay_layout_code', true ),
					get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true ),
					get_post_meta( $post_id, '_pronamic_gateway_ogone_user_id', true ),
				) );

				echo implode( ' ', $data );

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

				echo implode( ' ', $data );

				break;
			case 'pronamic_gateway_dashboard':
				$id = get_post_meta( $post_id, '_pronamic_gateway_id', true );

				if ( isset( $pronamic_pay_gateways[ $id ] ) ) {
					$urls = array();

					if ( isset( $pronamic_pay_gateways[ $id ]['dashboard_url'] ) ) {
						$url = $pronamic_pay_gateways[ $id ]['dashboard_url'];

						$urls[ $url ] = __( 'Dashboard', 'pronamic_ideal' );
					}

					if ( isset( $pronamic_pay_gateways[ $id ]['test'], $pronamic_pay_gateways[ $id ]['test']['dashboard_url'] ) ) {
						$url = $pronamic_pay_gateways[ $id ]['test']['dashboard_url'];

						$urls[ $url ] = __( 'Test', 'pronamic_ideal' );
					}

					if ( isset( $pronamic_pay_gateways[ $id ]['live'], $pronamic_pay_gateways[ $id ]['live']['dashboard_url'] ) ) {
						$url = $pronamic_pay_gateways[ $id ]['live']['dashboard_url'];

						$urls[ $url ] = __( 'Production', 'pronamic_ideal' );
					}

					// Output
					$content = array();

					foreach ( $urls as $url => $name ) {
						$content[] = sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $url ),
							esc_html( $name )
						);
					}

					echo implode( ' | ', $content );
				}

				break;
		}
	}

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
			if ( 'pronamic_gateway' == $post->post_type ) {
				echo ' enctype="multipart/form-data"';
			}
		}
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'pronamic_gateway_config',
			__( 'Configuration', 'pronamic_ideal' ),
			array( $this, 'meta_box_config' ),
			'pronamic_gateway',
			'normal',
			'high'
		);

		add_meta_box(
			'pronamic_gateway_test',
			__( 'Test', 'pronamic_ideal' ),
			array( $this, 'meta_box_test' ),
			'pronamic_gateway',
			'normal',
			'high'
		);
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_config( $post ) {
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

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post( $post_id ) {
		// Check if our nonce is set.
		if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			return $post_id;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_gateway' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, its safe for us to save the data now. */
		$definition = array(
			// General
			'_pronamic_gateway_id'   => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_mode' => FILTER_SANITIZE_STRING,
			// iDEAL
			'_pronamic_gateway_ideal_merchant_id' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ideal_sub_id'      => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ideal_purchase_id' => FILTER_SANITIZE_STRING,
			// iDEAL Basic
			'_pronamic_gateway_ideal_hash_key'    => FILTER_SANITIZE_STRING,
			// PayDutch
			'_pronamic_gateway_paydutch_username' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_paydutch_password' => FILTER_SANITIZE_STRING,
			// Mollie
			'_pronamic_gateway_mollie_api_key'     => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_mollie_partner_id'  => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_mollie_profile_key' => FILTER_SANITIZE_STRING,
			// MultiSafepay
			'_pronamic_gateway_multisafepay_account_id' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_multisafepay_site_id'    => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_multisafepay_site_code'  => FILTER_SANITIZE_STRING,
			// OmniKassa
			'_pronamic_gateway_omnikassa_merchant_id' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_omnikassa_secret_key'  => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_omnikassa_key_version' => FILTER_SANITIZE_STRING,
			// Buckaroo
			'_pronamic_gateway_buckaroo_website_key' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_buckaroo_secret_key'  => FILTER_SANITIZE_STRING,
			// ICEPAY
			'_pronamic_gateway_icepay_merchant_id' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_icepay_secret_code' => FILTER_SANITIZE_STRING,
			// Pay.nl
			'_pronamic_gateway_pay_nl_token'      => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_pay_nl_service_id' => FILTER_SANITIZE_STRING,
			// Sisow
			'_pronamic_gateway_sisow_merchant_id'  => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_sisow_merchant_key' => FILTER_SANITIZE_STRING,
			// TargetPay
			'_pronamic_gateway_targetpay_layoutcode' => FILTER_SANITIZE_STRING,
			// Ogone
			'_pronamic_gateway_ogone_psp_id'              => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_hash_algorithm'      => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_sha_in_pass_phrase'  => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_sha_out_pass_phrase' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_user_id'             => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_password'            => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_order_id'            => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_param_var'           => FILTER_SANITIZE_STRING,
			// Ogone DirectLink
			'_pronamic_gateway_ogone_directlink_sha_in_pass_phrase' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ogone_3d_secure_enabled' => FILTER_VALIDATE_BOOLEAN,
			// Qantani
			'_pronamic_gateway_qantani_merchant_id'     => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_qantani_merchant_secret' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_qantani_merchant_key'    => FILTER_SANITIZE_STRING,
			// Advanced
			'_pronamic_gateway_ideal_private_key_password' => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ideal_private_key'          => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_ideal_private_certificate'  => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_number_days_valid'          => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_country'                    => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_state_or_province'          => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_locality'                   => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_organization'               => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_organization_unit'          => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_common_name'                => FILTER_SANITIZE_STRING,
			'_pronamic_gateway_email'                      => FILTER_SANITIZE_STRING,
		);

		$data = filter_input_array( INPUT_POST, $definition );

		// Files
		$files = array(
			'_pronamic_gateway_ideal_private_key_file'         => '_pronamic_gateway_ideal_private_key',
			'_pronamic_gateway_ideal_private_certificate_file' => '_pronamic_gateway_ideal_private_certificate',
		);

		foreach ( $files as $name => $meta_key ) {
			if ( isset( $_FILES[ $name ] ) && $_FILES[ $name ]['error'] == UPLOAD_ERR_OK ) {
				$value = file_get_contents( $_FILES[ $name ]['tmp_name'] );

				$data[ $meta_key ] = $value;
			}
		}

		// Update post meta data
		pronamic_pay_update_post_meta_data( $post_id, $data );

		// Transient
		delete_transient( 'pronamic_pay_issuers_' . $post_id );
	}
}
