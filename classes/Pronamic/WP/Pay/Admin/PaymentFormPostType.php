<?php

/**
 * Title: WordPress admin payment form post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_Admin_PaymentFormPostType {
	public function __construct() {
		add_filter( 'manage_edit-pronamic_pay_gf_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_pronamic_pay_gf_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}
	
	public function edit_columns( $columns ) {
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
	
	public function custom_columns( $column, $post_id ) {
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

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'pronamic_pay_gf',
			__( 'Configuration', 'pronamic_ideal' ),
			array( $this, 'meta_box_config' ),
			'pronamic_pay_gf',
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
		include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/gravityforms/feed-edit.php';
	}
}
