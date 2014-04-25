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
		'supports'           => array( 'title', 'revisions' ),
		'rewrite'            => false,
		'query_var'          => false,
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
		'query_var'          => false,
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
		'supports'           => array( 'title', 'revisions' ),
		'rewrite'            => false,
		'query_var'          => false,
	) );
}

add_action( 'init', 'pronamic_pay_create_initial_post_types', 20 ); // highest priority

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
