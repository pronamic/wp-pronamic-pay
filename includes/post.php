<?php

function pronamic_payment_gateways_create_initial_post_types() {
	register_post_type( 'pronamic_gateway', array(
		'label'              => __( 'Gateways', 'pronamic_ideal' ),
		'labels'             => array(
			'name'               => __( 'Gateways', 'pronamic_ideal' ),
			'singular_name'      => __( 'Gateway', 'pronamic_ideal' ),
			'add_new'            => __( 'Add New', 'pronamic_ideal' ),
			'add_new_item'       => __( 'Add New Gateway', 'pronamic_ideal' ),
			'edit_item'          => __( 'Edit Gateway', 'pronamic_ideal' ),
			'new_item'           => __( 'New Gateway', 'pronamic_ideal' ),
			'all_items'          => __( 'All Gateways', 'pronamic_ideal' ),
			'view_item'          => __( 'View Gateway', 'pronamic_ideal' ),
			'search_items'       => __( 'Search Gateways', 'pronamic_ideal' ),
			'not_found'          => __( 'No gateways found', 'pronamic_ideal' ),
			'not_found_in_trash' => __( 'No gateways found in Trash', 'pronamic_ideal' ),
			'menu_name'          => __( 'Gateways', 'pronamic_ideal' )
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
		'supports'           => array(
			
		),
		'rewrite'            => false,
		'query_var'          => false
	) );
}

add_action( 'init', 'pronamic_payment_gateways_create_initial_post_types', 20 ); // highest priority



function pronamic_gateway_columns( $columns ) {
	$columns = array(
		'cb'                           => '<input type="checkbox" />',
		'title'                        => __( 'Title', 'pronamic_ideal' ),
		'pronamic_gateway_variant'     => __( 'Variant', 'pronamic_ideal' ),
		'pronamic_gateway_id'          => __( 'ID', 'pronamic_ideal' ),
		'pronamic_gateway_secret'      => __( 'Secret', 'pronamic_ideal' ),
		'pronamic_gateway_dashboard'   => __( 'Dashboard', 'pronamic_ideal' ),
		'date'                         => __( 'Date', 'pronamic_ideal' )
	);

	return $columns;
}

add_filter( 'manage_edit-pronamic_gateway_columns', 'pronamic_gateway_columns' ) ;


function pronamic_gateway_custom_column( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'pronamic_gateway_variant':
			echo get_post_meta( $post_id, '_pronamic_gateway_variant_id', true );

			break;
		case 'pronamic_gateway_id':
			$data = array_filter( array(
				get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true ),
				get_post_meta( $post_id, '_pronamic_gateway_omnikassa_merchant_id', true ),
				get_post_meta( $post_id, '_pronamic_gateway_buckaroo_website_key', true ),
				get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true ),
				get_post_meta( $post_id, '_pronamic_gateway_mollie_partner_id', true ),
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
			echo '?';
			
			break;
	}
}

add_action( 'manage_pronamic_gateway_posts_custom_column', 'pronamic_gateway_custom_column', 10, 2 );


function pronamic_payment_columns( $columns ) {
	$columns = array(
		'cb'                           => '<input type="checkbox" />',
		'title'                        => __( 'Title', 'pronamic_ideal' ),
		'pronamic_payment_description' => __( 'Description', 'pronamic_ideal' ),
		'pronamic_payment_amount'      => __( 'Amount', 'pronamic_ideal' ),
		'pronamic_payment_consumer'    => __( 'Consumer', 'pronamic_ideal' ),
		'pronamic_payment_source'      => __( 'Source', 'pronamic_ideal' ),
		'pronamic_payment_status'      => __( 'Status', 'pronamic_ideal' ),
		'date'                         => __( 'Date', 'pronamic_ideal' )
	);

	return $columns;
}

add_filter( 'manage_edit-pronamic_payment_columns', 'pronamic_payment_columns' ) ;


function pronamic_payment_custom_column( $column, $post_id ) {
	global $post;

	switch( $column ) {
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
			$source    = get_post_meta( $post_id, '_pronamic_payment_source', true );
			$source_id = get_post_meta( $post_id, '_pronamic_payment_source_id', true );

			$text = $source . '<br />' . $source_id;

			// $text = apply_filters( 'pronamic_ideal_source_column_' . $source, $text, $post );
			// $text = apply_filters( 'pronamic_ideal_source_column', $text, $post );
				
			echo $text;

			break;
		case 'pronamic_payment_status':
			$status = get_post_meta( $post_id, '_pronamic_payment_status', true );

			echo Pronamic_WordPress_IDeal_IDeal::translate_status( $status );

			break;
	}
}

add_action( 'manage_pronamic_payment_posts_custom_column', 'pronamic_payment_custom_column', 10, 2 );


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function myplugin_add_custom_box() {
	add_meta_box(
		'pronamic_gateway_config',
		__( 'Configuration', 'pronamic_ideal' ),
		'myplugin_inner_custom_box',
		'pronamic_gateway',
		'normal',
		'high'
	);
}

add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function myplugin_inner_custom_box( $post ) {
	include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/configuration-edit.php';
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
		'_pronamic_gateway_mode'              => FILTER_SANITIZE_STRING,
		// Basic
		'_pronamic_gateway_ideal_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ideal_sub_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ideal_hash_key' => FILTER_SANITIZE_STRING,
		// Mollie
		'_pronamic_gateway_mollie_partner_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_mollie_profile_key' => FILTER_SANITIZE_STRING,
		// OmniKassa
		'_pronamic_gateway_omnikassa_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_omnikassa_secret_key' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_omnikassa_key_version' => FILTER_SANITIZE_STRING,
		// Buckaroo
		'_pronamic_gateway_buckaroo_website_key' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_buckaroo_secret_key' => FILTER_SANITIZE_STRING,
		// ICEPAY
		'_pronamic_gateway_icepay_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_icepay_secret_code' => FILTER_SANITIZE_STRING,
		// Sisow
		'_pronamic_gateway_sisow_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_sisow_merchant_key' => FILTER_SANITIZE_STRING,
		// TargetPay
		'_pronamic_gateway_targetpay_layoutcode' => FILTER_SANITIZE_STRING,
		// Ogone
		'_pronamic_gateway_ogone_pspid' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ogone_sha_in' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ogone_sha_out' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ogone_user_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ogone_password' => FILTER_SANITIZE_STRING,
		// Qantani
		'_pronamic_gateway_qantani_merchant_id' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_qantani_merchant_secret' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_qantani_merchant_key' => FILTER_SANITIZE_STRING,
		// Advanced
		'_pronamic_gateway_ideal_private_key_password' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ideal_private_key' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_ideal_private_certificate' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_number_days_valid' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_country' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_state_or_province' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_locality' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_organization' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_organization_unit' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_common_name' => FILTER_SANITIZE_STRING,
		'_pronamic_gateway_email' => FILTER_SANITIZE_STRING
	);
	
	$data = filter_input_array( INPUT_POST, $definition );

	foreach ( $data as $key => $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
	
	// Transient
	delete_transient( 'pronamic_ideal_issuers_' . $post_id );
}

add_action( 'save_post', 'pronamic_pay_save_gateway' );