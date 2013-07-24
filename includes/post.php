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
			'title', 'comments'
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
			'comments'
		),
		'rewrite'            => false,
		'query_var'          => false
	) );
}

// add_action( 'init', 'pronamic_payment_gateways_create_initial_post_types', 20 ); // highest priority
