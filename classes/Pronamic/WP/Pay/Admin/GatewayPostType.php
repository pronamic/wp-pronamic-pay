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
	}

	public function edit_columns( $columns ) {
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
	
	function custom_columns( $column, $post_id ) {
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
	function meta_box_config( $post ) {
		include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/config-edit.php';
	}
	
	/**
	 * Pronamic Pay gateway test meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	function meta_box_test( $post ) {
		include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/config-test.php';
	}
}
