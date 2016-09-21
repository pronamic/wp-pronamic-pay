<?php

/**
 * Title: WordPress admin payment post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_PaymentPostType {
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_payment';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin payment post type object
	 */
	public function __construct() {
		add_filter( 'request', array( $this, 'request' ) );

		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'columns' ) );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_sortable_columns', array( $this, 'sortable_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		add_filter( 'default_hidden_columns', array( $this, 'default_hidden_columns' ) );

		// Transition Post Status
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );

		// Bulk Actions
		$this->bulk_actions = new Pronamic_WP_Pay_Admin_PaymentBulkActions();
	}

	//////////////////////////////////////////////////

	/**
	 * Filters and sorting handler
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-post-types.php#L1585-L1596
	 *
	 * @param  array $vars
	 * @return array
	 */
	public function request( $vars ) {
		$screen = get_current_screen();

		if ( self::POST_TYPE === $screen->post_type ) {
			if ( ! isset( $vars['post_status'] ) ) {
				$vars['post_status'] = array_keys( Pronamic_WP_Pay_Plugin::get_payment_states() );

				$vars['post_status'][] = 'publish';
			}
		}

		return $vars;
	}

	/**
	 * Pre get posts.
	 *
	 * @param WP_Query $query
	 */
	public function pre_get_posts( $query ) {
		if ( 'pronamic_payment_amount' == $query->get( 'orderby' ) ) {  
			$query->set( 'meta_key', '_pronamic_payment_amount' );
			$query->set( 'orderby', 'meta_value_num' );  
		}
	}

	//////////////////////////////////////////////////

	public function columns( $columns ) {
		$columns = array(
			'cb'                           => '<input type="checkbox" />',
			'pronamic_payment_status'      => sprintf(
				'<span class="pronamic-pay-tip pronamic-pay-status pronamic-pay-status" data-tip="%s">%s</span>',
				esc_html__( 'Status', 'pronamic_ideal' ),
				esc_html__( 'Status', 'pronamic_ideal' )
			),
			'pronamic_payment_title'       => __( 'Payment', 'pronamic_ideal' ),
			'pronamic_payment_transaction' => __( 'Transaction', 'pronamic_ideal' ),
			'pronamic_payment_gateway'     => __( 'Gateway', 'pronamic_ideal' ),
			'pronamic_payment_description' => __( 'Description', 'pronamic_ideal' ),
			'pronamic_payment_customer'    => __( 'Customer', 'pronamic_ideal' ),
			'pronamic_payment_amount'      => __( 'Amount', 'pronamic_ideal' ),
			'pronamic_payment_date'        => __( 'Date', 'pronamic_ideal' ),

//			'pronamic_payment_recurring'   => __( 'Recurring', 'pronamic_ideal' ),
//			'pronamic_payment_source'      => __( 'Source', 'pronamic_ideal' ),
//			'author'                       => __( 'User', 'pronamic_ideal' ),
//			'date'                         => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	public function default_hidden_columns( $hidden ) {
		$hidden[] = 'pronamic_payment_gateway';
		$hidden[] = 'pronamic_payment_description';

		return $hidden;
	}

	public function sortable_columns( $sortable_columns ) {
		$sortable_columns['pronamic_payment_title']  = 'ID';
		$sortable_columns['pronamic_payment_amount'] = 'pronamic_payment_amount';
		$sortable_columns['pronamic_payment_date']   = 'date';

		return $sortable_columns;
	}

	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
			case 'pronamic_payment_status':
				$post_status = get_post_status( $post_id );

				$label = __( 'Unknown', 'pronamic_ideal' );

				$status_object = get_post_status_object( $post_status );

				if ( isset( $status_object, $status_object->label ) ) {
					$label = $status_object->label;
				}

				printf(
					'<span class="pronamic-pay-tip pronamic-pay-status pronamic-pay-status-%s" data-tip="%s">%s</span>',
					esc_attr( $post_status ),
					esc_attr( $label ),
					esc_html( $label )
				);

				break;
			case 'pronamic_payment_title':
				$payment = get_pronamic_payment( $post_id );

				$source    = $payment->get_source();
				$source_id = $payment->get_source_id();

				$text = $source;

				switch ( $source ) {
					case 'woocommerce' :
						$text = __( 'WooCommerce Order', 'pronamic_ideal' );

						break;
					case 'gravityforms' :
					case 'gravityformsideal' :
						$text = __( 'Gravity Forms Entry', 'pronamic_ideal' );

						break;
					case 'test' :
						$text = __( 'Test', 'pronamic_ideal' );

						break;
				}

				printf(
					__( '%s for %s %s', 'pronamic_ideal' ),
					sprintf(
						'<a href="%s" class="row-title"><strong>#%s</strong></a>',
						get_edit_post_link( $post_id ),
						$post_id
					),
					$text,
					sprintf(
						'<a href="%s">#%s</a>',
						get_edit_post_link( $source_id ),
						$source_id
					)
				);

				break;
			case 'pronamic_payment_gateway':
				$config_id = get_post_meta( $post_id, '_pronamic_payment_config_id', true );

				if ( ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_payment_transaction':
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_transaction_id', true ) );

				break;
			case 'pronamic_payment_description':
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_description', true ) );

				break;
			case 'pronamic_payment_amount':
				$currency = get_post_meta( $post_id, '_pronamic_payment_currency', true );
				$amount   = get_post_meta( $post_id, '_pronamic_payment_amount', true );

				echo esc_html( Pronamic_WP_Util::format_price( $amount, $currency ) );

				break;
			case 'pronamic_payment_date':
				echo esc_html( get_the_time( __( 'D j M Y \a\t H:i', 'pronamic_ideal' ), $post_id ) );

				break;
			case 'pronamic_payment_recurring':
				$recurring = get_post_meta( $post_id, '_pronamic_payment_recurring', true );

				if ( $recurring ) {
					echo esc_html_e( 'Yes', 'pronamic_ideal' );

					return;
				}

				$subscription_id = get_post_meta( $post_id, '_pronamic_payment_subscription_id', true );

				if ( $subscription_id ) {
					printf(
						'%s %s',
						esc_html( get_post_meta( $subscription_id, '_pronamic_subscription_currency', true ) ),
						esc_html( get_post_meta( $subscription_id, '_pronamic_subscription_amount', true ) )
					);
				}

				break;
			case 'pronamic_payment_consumer':
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_name', true ) );
				echo '<br />';
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_account_number', true ) );
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true ) );
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true ) );
				echo '<br />';
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_city', true ) );

				break;
			case 'pronamic_payment_customer':
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_customer_name', true ) );

				break;
			case 'pronamic_payment_source':
				$payment = get_pronamic_payment( $post_id );

				echo $payment->get_source_text(); //xss ok

				break;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type ) {
		if ( self::POST_TYPE === $post_type ) {
			add_meta_box(
				'pronamic_payment',
				__( 'Payment', 'pronamic_ideal' ),
				array( $this, 'meta_box_info' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_payment_subscription',
				__( 'Subscription', 'pronamic_ideal' ),
				array( $this, 'meta_box_subscription' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_payment_log',
				__( 'Log', 'pronamic_ideal' ),
				array( $this, 'meta_box_log' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_payment_update',
				__( 'Update', 'pronamic_ideal' ),
				array( $this, 'meta_box_update' ),
				$post_type,
				'side',
				'high'
			);

			// @see http://kovshenin.com/2012/how-to-remove-the-publish-box-from-a-post-type/
			remove_meta_box( 'submitdiv', $post_type, 'side' );
		}
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
	public function meta_box_log( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-log.php';
	}

	/**
	 * Pronamic Pay payment subscription meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_subscription( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-subscription.php';
	}

	/**
	 * Pronamic Pay gateway update meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_update( $post ) {
		wp_nonce_field( 'pronamic_payment_update', 'pronamic_payment_update_nonce' );

		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-payment-update.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Post row actions
	 */
	public function post_row_actions( $actions, $post ) {
		if ( self::POST_TYPE === $post->post_type ) {
			return array();
		}

		return $actions;
	}

	//////////////////////////////////////////////////

	/**
	 * Translate post status to meta status
	 *
	 * @param string $post_status
	 * @return string
	 */
	private function translate_post_status_to_meta_status( $post_status ) {
		switch ( $post_status ) {
			case 'payment_pending' :
				return Pronamic_WP_Pay_Statuses::OPEN;
			case 'payment_processing' :
				return Pronamic_WP_Pay_Statuses::OPEN;
			case 'payment_on_hold' :
				return null;
			case 'payment_completed' :
				return Pronamic_WP_Pay_Statuses::SUCCESS;
			case 'payment_cancelled' :
				return Pronamic_WP_Pay_Statuses::CANCELLED;
			case 'payment_refunded' :
				return null;
			case 'payment_failed' :
				return Pronamic_WP_Pay_Statuses::FAILURE;
			case 'payment_expired' :
				return Pronamic_WP_Pay_Statuses::EXPIRED;
		}
	}

	/**
	 * Transition post status
	 *
	 * @param string $new_status
	 * @param string $old_status
	 * @param \WP_Post $post
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		if (
			filter_has_var( INPUT_POST, 'pronamic_payment_update_nonce' )
				&&
			check_admin_referer( 'pronamic_payment_update', 'pronamic_payment_update_nonce' )
				&&
			 'pronamic_payment' === get_post_type( $post )
		) {
			$can_redirect = false;

			$old_status_meta = $this->translate_post_status_to_meta_status( $old_status );
			$new_status_meta = $this->translate_post_status_to_meta_status( $new_status );

			update_post_meta( $post->ID, '_pronamic_payment_status', $new_status_meta );

			$payment = get_pronamic_payment( $post->ID );

			do_action( 'pronamic_payment_status_update_' . $payment->source . '_' . strtolower( $old_status_meta ) . '_to_' . strtolower( $new_status_meta ), $payment, $can_redirect );
			do_action( 'pronamic_payment_status_update_' . $payment->source, $payment, $can_redirect );
			do_action( 'pronamic_payment_status_update', $payment, $can_redirect );
		}
	}

	/**
	 * Get capabilities for this post type.
	 *
	 * @return array
	 */
	public static function get_capabilities() {
		return array(
			'edit_post'              => 'edit_payment',
			'read_post'              => 'read_payment',
			'delete_post'            => 'delete_payment',
			'edit_posts'             => 'edit_payments',
			'edit_others_posts'      => 'edit_other_payments',
			'publish_posts'          => 'publish_payments',
			'read_private_posts'     => 'read_private_payments',
			'read'                   => 'read',
			'delete_posts'           => 'delete_payments',
			'delete_private_posts'   => 'delete_private_payments',
			'delete_published_posts' => 'delete_published_payments',
			'delete_others_posts'    => 'delete_others_payments',
			'edit_private_posts'     => 'edit_private_payments',
			'edit_published_posts'   => 'edit_published_payments',
			'create_posts'           => 'create_payments',
		);
	}
}
