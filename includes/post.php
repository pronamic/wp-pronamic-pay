<?php

function pronamic_pay_create_initial_post_types() {
	register_post_type( 'pronamic_gateway', array(
		'label'              => __( 'Payment Gateway Configurations', 'pronamic_ideal' ),
		'labels'             => array(
			'name'               => __( 'Payment Gateway Configurations', 'pronamic_ideal' ),
			'singular_name'      => __( 'Payment Gateway Configuration', 'pronamic_ideal' ),
			'add_new'            => __( 'Add New', 'pronamic_ideal' ),
			'add_new_item'       => __( 'Add New Payment Gateway Configuration', 'pronamic_ideal' ),
			'edit_item'          => __( 'Edit Payment Gateway Configuration', 'pronamic_ideal' ),
			'new_item'           => __( 'New Payment Gateway Configuration', 'pronamic_ideal' ),
			'all_items'          => __( 'All Payment Gateway Configurations', 'pronamic_ideal' ),
			'view_item'          => __( 'View Payment Gateway Configuration', 'pronamic_ideal' ),
			'search_items'       => __( 'Search Payment Gateway Configurations', 'pronamic_ideal' ),
			'not_found'          => __( 'No payment gateway configurations found', 'pronamic_ideal' ),
			'not_found_in_trash' => __( 'No payment gateway configurations found in Trash', 'pronamic_ideal' ),
			'menu_name'          => __( 'Configurations', 'pronamic_ideal' )
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_nav_menus'  => false,
		'show_in_menu'       => false,
		'show_in_admin_bar'  => false,
		'supports'           => array(
			'title', 'revisions'
		),
		'rewrite'            => false,
		'query_var'          => false
	) );

	register_post_type( 'pronamic_payment', array(
		'label'              => __( 'Payments', 'pronamic_ideal' ),
		'labels'             => array(
			'name'               => __( 'Payments', 'pronamic_ideal' ),
			'singular_name'      => __( 'Payment', 'pronamic_ideal' ),
			'add_new'            => __( 'Add New', 'pronamic_ideal' ),
			'add_new_item'       => __( 'Add New Payment', 'pronamic_ideal' ),
			'edit_item'          => __( 'Edit Payment', 'pronamic_ideal' ),
			'new_item'           => __( 'New Payment', 'pronamic_ideal' ),
			'all_items'          => __( 'All Payments', 'pronamic_ideal' ),
			'view_item'          => __( 'View Payment', 'pronamic_ideal' ),
			'search_items'       => __( 'Search Payments', 'pronamic_ideal' ),
			'not_found'          => __( 'No payments found', 'pronamic_ideal' ),
			'not_found_in_trash' => __( 'No payments found in Trash', 'pronamic_ideal' ),
			'menu_name'          => __( 'Payments', 'pronamic_ideal' )
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_nav_menus'  => false,
		'show_in_menu'       => false,
		'show_in_admin_bar'  => false,
		'supports'           => false,
		'rewrite'            => false,
		'query_var'          => false
	) );

	register_post_type( 'pronamic_pay_gf', array(
		'label'              => __( 'Payment Forms', 'pronamic_ideal' ),
		'labels'             => array(
			'name'               => __( 'Payment Forms', 'pronamic_ideal' ),
			'singular_name'      => __( 'Payment Form', 'pronamic_ideal' ),
			'add_new'            => __( 'Add New', 'pronamic_ideal' ),
			'add_new_item'       => __( 'Add New Payment Form', 'pronamic_ideal' ),
			'edit_item'          => __( 'Edit Payment Form', 'pronamic_ideal' ),
			'new_item'           => __( 'New Payment Form', 'pronamic_ideal' ),
			'all_items'          => __( 'All Payment Forms', 'pronamic_ideal' ),
			'view_item'          => __( 'View Payment Form', 'pronamic_ideal' ),
			'search_items'       => __( 'Search Payment Forms', 'pronamic_ideal' ),
			'not_found'          => __( 'No payment forms found', 'pronamic_ideal' ),
			'not_found_in_trash' => __( 'No payment forms found in Trash', 'pronamic_ideal' ),
			'menu_name'          => __( 'Payment Forms', 'pronamic_ideal' )
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_nav_menus'  => false,
		'show_in_menu'       => false,
		'show_in_admin_bar'  => false,
		'supports'           => array(
			'title', 'revisions'
		),
		'rewrite'            => false,
		'query_var'          => false
	) );
}

add_action( 'init', 'pronamic_pay_create_initial_post_types', 20 ); // highest priority

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function pronamic_pay_save_gateway( $post_id ) {
	// Check if our nonce is set.
	if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) )
		return $post_id;

	$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_gateway' ) )
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	/* OK, its safe for us to save the data now. */
	$definition = array(
		// General
		'_pronamic_gateway_id'   => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_mode' => FILTER_SANITIZE_STRING,
		// Basic
		'_pronamic_gateway_ideal_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ideal_sub_id'      => FILTER_SANITIZE_STRING,
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
		'_pronamic_gateway_email'                      => FILTER_SANITIZE_STRING
	);
	
	$data = filter_input_array( INPUT_POST, $definition );

	// Files
	$files = array(
		'_pronamic_gateway_ideal_private_key_file'         => '_pronamic_gateway_ideal_private_key',
		'_pronamic_gateway_ideal_private_certificate_file' => '_pronamic_gateway_ideal_private_certificate'
	);

	foreach ( $files as $name => $meta_key ) {
		if ( isset( $_FILES[ $name ] ) && $_FILES[ $name ]['error'] == UPLOAD_ERR_OK ) {
			$value = file_get_contents( $_FILES[ $name ]['tmp_name'] );
			
			$data[$meta_key] = $value;
		}
	}

	// Update post meta data
	pronamic_pay_update_post_meta_data( $post_id, $data );

	// Transient
	delete_transient( 'pronamic_pay_issuers_' . $post_id );
}

add_action( 'save_post', 'pronamic_pay_save_gateway' );

/**
 * Helper function to update post meta data
 * 
 * @param int $post_id
 * @param array $data
 */
function pronamic_pay_update_post_meta_data( $post_id, array $data ) {
	/*
	 * Post meta values are passed through the stripslashes() function
	 * upon being stored, so you will need to be careful when passing
	 * in values (such as JSON) that might include \ escaped characters.
	 *
	 * @see http://codex.wordpress.org/Function_Reference/update_post_meta
	 */
	$data = wp_slash( $data );

	// Meta
	foreach ( $data as $key => $value ) {
		if ( isset( $value ) && $value != '' ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function pronamic_pay_save_pay_gf( $post_id ) {
	// Check if our nonce is set.
	if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) )
		return $post_id;

	$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_pay_gf' ) )
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	/* OK, its safe for us to save the data now. */
	$definition = array(
		'_pronamic_pay_gf_form_id'                            => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_config_id'                          => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_entry_id_prefix'                    => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_transaction_description'            => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_enabled'                  => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_condition_field_id'                 => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_operator'                 => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_value'                    => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_delay_notification_ids'             => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_delay_post_creation'                => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_delay_campaignmonitor_subscription' => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_delay_mailchimp_subscription'       => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_delay_user_registration'            => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_fields'                             => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_links' => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_user_role_field_id'                 => FILTER_SANITIZE_STRING
	);
	
	$data = filter_input_array( INPUT_POST, $definition );

	// Update post meta data
	pronamic_pay_update_post_meta_data( $post_id, $data );
}

add_action( 'save_post', 'pronamic_pay_save_pay_gf' );
