<?php

/**
 * Title: WordPress admin payment post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_Admin_PaymentPostType {
	public function __construct() {
		add_filter( 'manage_edit-pronamic_payment_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_pronamic_payment_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
	}

	public function edit_columns( $columns ) {
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
			'date'                         => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
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

				echo Pronamic_WP_Pay_Plugin::translate_status( $status );

				break;
		}
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'pronamic_payment',
			__( 'Payment', 'pronamic_ideal' ),
			array( $this, 'meta_box_info' ),
			'pronamic_payment',
			'normal',
			'high'
		);

		add_meta_box(
			'pronamic_payment_source',
			__( 'Source', 'pronamic_ideal' ),
			array( $this, 'meta_box_source' ),
			'pronamic_payment',
			'normal',
			'high'
		);

		add_meta_box(
			'pronamic_payment_log',
			__( 'Log', 'pronamic_ideal' ),
			array( $this, 'meta_box_log' ),
			'pronamic_payment',
			'normal',
			'high'
		);

		// @see http://kovshenin.com/2012/how-to-remove-the-publish-box-from-a-post-type/
		remove_meta_box( 'submitdiv', 'pronamic_payment', 'side' );
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_info( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-info.php';
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_source( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-source.php';
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_log( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-log.php';
	}

	/**
	 * Post row actions
	 */
	public function post_row_actions( $actions, $post ) {
		if ( 'pronamic_payment' === $post->post_type ) {
			return array();
		}

		return $actions;
	}
}
