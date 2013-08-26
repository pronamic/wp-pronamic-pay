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

add_action( 'init', 'pronamic_payment_gateways_create_initial_post_types', 20 ); // highest priority



function my_edit_movie_columns( $columns ) {

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

add_filter( 'manage_edit-pronamic_payment_columns', 'my_edit_movie_columns' ) ;

function my_manage_movie_columns( $column, $post_id ) {
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
			echo get_post_meta( $post_id, '_pronamic_payment_source', true );
			echo '<br />';
			echo get_post_meta( $post_id, '_pronamic_payment_source_id', true );
			break;
		case 'pronamic_payment_status':
			echo get_post_meta( $post_id, '_pronamic_payment_status', true );
			break;
	}
}

add_action( 'manage_pronamic_payment_posts_custom_column', 'my_manage_movie_columns', 10, 2 );
