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



function pronamic_pay_gateway_columns( $columns ) {
	$columns = array(
		'cb'                           => '<input type="checkbox" />',
		'title'                        => __( 'Title', 'pronamic_ideal' ),
		'pronamic_gateway_variant'     => __( 'Variant', 'pronamic_ideal' ),
		'pronamic_gateway_id'          => __( 'ID', 'pronamic_ideal' ),
		// 'pronamic_gateway_secret'      => __( 'Secret', 'pronamic_ideal' ),
		'pronamic_gateway_dashboard'   => __( 'Dashboard', 'pronamic_ideal' ),
		'date'                         => __( 'Date', 'pronamic_ideal' )
	);

	return $columns;
}

add_filter( 'manage_edit-pronamic_gateway_columns', 'pronamic_pay_gateway_columns' ) ;


function pronamic_pay_gateway_custom_column( $column, $post_id ) {
	global $post;
	global $pronamic_pay_gateways;

	switch( $column ) {
		case 'pronamic_gateway_variant':
			$id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
			
			if ( isset( $pronamic_pay_gateways[$id] ) ) {
				echo $pronamic_pay_gateways[$id]['name'];
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

			if ( isset( $pronamic_pay_gateways[$id] ) ) {
				$urls = array();

				if ( isset( $pronamic_pay_gateways[$id]['dashboard_url'] ) ) {
					$url = $pronamic_pay_gateways[$id]['dashboard_url'];
					
					$urls[$url] = __( 'Dashboard', 'pronamic_ideal' );
				}

				if ( isset( $pronamic_pay_gateways[$id]['test'], $pronamic_pay_gateways[$id]['test']['dashboard_url'] ) ) {
					$url = $pronamic_pay_gateways[$id]['test']['dashboard_url'];

					$urls[$url] = __( 'Test', 'pronamic_ideal' );
				}

				if ( isset( $pronamic_pay_gateways[$id]['live'], $pronamic_pay_gateways[$id]['live']['dashboard_url'] ) ) {
					$url = $pronamic_pay_gateways[$id]['live']['dashboard_url'];

					$urls[$url] = __( 'Production', 'pronamic_ideal' );					
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

add_action( 'manage_pronamic_gateway_posts_custom_column', 'pronamic_pay_gateway_custom_column', 10, 2 );


function pronamic_pay_payment_columns( $columns ) {
	$columns = array(
		'cb'                           => '<input type="checkbox" />',
		'title'                        => __( 'Title', 'pronamic_ideal' ),
		'pronamic_payment_gateway'     => __( 'Gateway', 'pronamic_ideal' ),
		'pronamic_payment_transaction' => __( 'Transaction', 'pronamic_ideal' ),
		'pronamic_payment_description' => __( 'Description', 'pronamic_ideal' ),
		'pronamic_payment_amount'      => __( 'Amount', 'pronamic_ideal' ),
		'pronamic_payment_consumer'    => __( 'Consumer', 'pronamic_ideal' ),
		'pronamic_payment_source'      => __( 'Source', 'pronamic_ideal' ),
		'pronamic_payment_status'      => __( 'Status', 'pronamic_ideal' ),
		'date'                         => __( 'Date', 'pronamic_ideal' )
	);

	return $columns;
}

add_filter( 'manage_edit-pronamic_payment_columns', 'pronamic_pay_payment_columns' ) ;


function pronamic_pay_payment_custom_column( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'pronamic_payment_gateway':
			$config_id = get_post_meta( $post_id, '_pronamic_payment_config_id', true );
			
			if ( ! empty( $config_id ) ) {
				echo get_the_title( $config_id );
			} else {
				echo '—';
			}

			break;
		case 'pronamic_payment_transaction':
			echo get_post_meta( $post_id, '_pronamic_payment_transaction_id', true );

			break;
		case 'pronamic_payment_description':
			echo get_post_meta( $post_id, '_pronamic_payment_description', true );

			break;
		case 'pronamic_payment_amount':
			echo get_post_meta( $post_id, '_pronamic_payment_currency', true );
			echo ' ';
			echo get_post_meta( $post_id, '_pronamic_payment_amount', true );

			break;
		case 'pronamic_payment_consumer':
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_name', true );
			echo '<br />';
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_account_number', true );
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true );
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true );
			echo '<br />';
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_city', true );

			break;
		case 'pronamic_payment_source':
			$payment = get_pronamic_payment( $post_id );

			echo $payment->get_source_text();

			break;
		case 'pronamic_payment_status':
			$status = get_post_meta( $post_id, '_pronamic_payment_status', true );

			echo Pronamic_WordPress_IDeal_IDeal::translate_status( $status );

			break;
	}
}

add_action( 'manage_pronamic_payment_posts_custom_column', 'pronamic_pay_payment_custom_column', 10, 2 );


function pronamic_pay_gf_columns( $columns ) {
	$columns = array(
		'cb'                                      => '<input type="checkbox" />',
		'title'                                   => __( 'Title', 'pronamic_ideal' ),
		'pronamic_pay_gf_form'                    => __( 'Form', 'pronamic_ideal' ),
		'pronamic_pay_gf_config'                  => __( 'Configuration', 'pronamic_ideal' ),
		'pronamic_pay_gf_transaction_description' => __( 'Transaction Description', 'pronamic_ideal' ),
		'date'                                    => __( 'Date', 'pronamic_ideal' )
	);

	return $columns;
}

add_filter( 'manage_edit-pronamic_pay_gf_columns', 'pronamic_pay_gf_columns' ) ;


function pronamic_pay_gf_custom_column( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'pronamic_pay_gf_form':
			$form_id = get_post_meta( $post_id, '_pronamic_pay_gf_form_id', true );
			
			if ( ! empty( $form_id ) ) {
				printf(
					'<a href="%s">%s</a>',
					add_query_arg( array(
						'page' => 'gf_edit_forms',
						'id'   => $form_id
					), admin_url( 'admin.php' ) ),
					get_pronamic_pay_gf_form_title( $form_id )
				);
			} else {
				echo '—';
			}

			break;
		case 'pronamic_pay_gf_config':
			$config_id = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

			if ( ! empty( $config_id ) ) {
				echo get_the_title( $config_id );
			} else {
				echo '—';
			}

			break;
		case 'pronamic_pay_gf_transaction_description':
			echo get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );

			break;
	}
}

add_action( 'manage_pronamic_pay_gf_posts_custom_column', 'pronamic_pay_gf_custom_column', 10, 2 );

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function pronamic_pay_meta_boxes() {
	add_meta_box(
		'pronamic_gateway_config',
		__( 'Configuration', 'pronamic_ideal' ),
		'pronamic_pay_gateway_config_meta_box',
		'pronamic_gateway',
		'normal',
		'high'
	);

	add_meta_box(
		'pronamic_gateway_test',
		__( 'Test', 'pronamic_ideal' ),
		'pronamic_pay_gateway_test_meta_box',
		'pronamic_gateway',
		'normal',
		'high'
	);

	add_meta_box(
		'pronamic_payment',
		__( 'Payment', 'pronamic_ideal' ),
		'pronamic_pay_payment_info_meta_box',
		'pronamic_payment',
		'normal',
		'high'
	);

	add_meta_box(
		'pronamic_payment_source',
		__( 'Source', 'pronamic_ideal' ),
		'pronamic_pay_payment_source_meta_box',
		'pronamic_payment',
		'normal',
		'high'
	);

	add_meta_box(
		'pronamic_payment_log',
		__( 'Log', 'pronamic_ideal' ),
		'pronamic_pay_payment_log_meta_box',
		'pronamic_payment',
		'normal',
		'high'
	);

	// @see http://kovshenin.com/2012/how-to-remove-the-publish-box-from-a-post-type/
	remove_meta_box( 'submitdiv', 'pronamic_payment', 'side' );

	add_meta_box(
		'pronamic_pay_gf',
		__( 'Configuration', 'pronamic_ideal' ),
		'pronamic_pay_gf_meta_box',
		'pronamic_pay_gf',
		'normal',
		'high'
	);
}

add_action( 'add_meta_boxes', 'pronamic_pay_meta_boxes' );

/**
 * Pronamic Pay gateway config meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_gateway_config_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/config-edit.php';
}

/**
 * Pronamic Pay gateway test meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_gateway_test_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/config-test.php';
}

/**
 * Pronamic Pay gateway config meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_payment_info_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/meta-box-payment-info.php';
}

/**
 * Pronamic Pay gateway config meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_payment_source_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/meta-box-payment-source.php';
}

/**
 * Pronamic Pay gateway config meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_payment_log_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/meta-box-payment-log.php';
}


/**
 * Pronamic Pay gateway config meta box
 *
 * @param WP_Post $post The object for the current post/page.
 */
function pronamic_pay_gf_meta_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/gravityforms/feed-edit.php';
}

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
	delete_transient( 'pronamic_ideal_issuers_' . $post_id );
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
		'_pronamic_pay_gf_form_id' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_config_id' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_transaction_description' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_enabled' => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_condition_field_id' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_operator' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_condition_value' => FILTER_SANITIZE_STRING,
		'_pronamic_pay_gf_delay_notification_ids' => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_delay_post_creation' => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_delay_campaignmonitor_subscription' => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_delay_mailchimp_subscription' => FILTER_VALIDATE_BOOLEAN,
		'_pronamic_pay_gf_fields' => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_links' => array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
		),
		'_pronamic_pay_gf_user_role_field_id' => FILTER_SANITIZE_STRING
	);
	
	$data = filter_input_array( INPUT_POST, $definition );

	// Update post meta data
	pronamic_pay_update_post_meta_data( $post_id, $data );
	
	// Transient
	delete_transient( 'pronamic_ideal_issuers_' . $post_id );
}

add_action( 'save_post', 'pronamic_pay_save_pay_gf' );

/**
 * Post edit form tag
 * 
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/edit-form-advanced.php#L299
 * @see https://github.com/WordPress/WordPress/blob/3.5.2/wp-admin/edit-form-advanced.php#L299
 * 
 * @param WP_Post $post (only available @since 3.5.2)
 */
function pronamic_pay_gateway_post_edit_form_tag( $post ) {
	if ( empty( $post ) ) {
		global $post;
	}

	if ( $post ) {
		if ( $post->post_type == 'pronamic_gateway' ) {
			echo ' enctype="multipart/form-data"';
		}
	}
}

add_action( 'post_edit_form_tag', 'pronamic_pay_gateway_post_edit_form_tag' );
