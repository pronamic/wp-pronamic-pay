<?php

/**
 * Title: WordPress admin subscription post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since unreleased
 */
class Pronamic_WP_Pay_Admin_SubscriptionPostType {
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_pay_subscr';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin payment post type object
	 */
	public function __construct() {
		add_filter( 'request', array( $this, 'request' ) );

		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		// Transition post status
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );
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
				$vars['post_status'] = array_keys( Pronamic_WP_Pay_Plugin::get_subscription_states() );

				$vars['post_status'][] = 'publish';
			}
		}

		return $vars;
	}

	//////////////////////////////////////////////////

	public function edit_columns( $columns ) {
		$columns = array(
			'cb'                                => '<input type="checkbox" />',
			'title'                             => __( 'Title', 'pronamic_ideal' ),
			'pronamic_subscription_gateway'     => __( 'Gateway', 'pronamic_ideal' ),
			'pronamic_subscription_description' => __( 'Description', 'pronamic_ideal' ),
			'pronamic_subscription_amount'      => __( 'Amount', 'pronamic_ideal' ),
			'pronamic_subscription_recurring'   => __( 'Recurrence', 'pronamic_ideal' ),
			'pronamic_subscription_status'      => __( 'Status', 'pronamic_ideal' ),
			'pronamic_subscription_source'      => __( 'Source', 'pronamic_ideal' ),
			'author'                            => __( 'User', 'pronamic_ideal' ),
			'date'                              => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
			case 'pronamic_subscription_gateway':
				$payment = get_pronamic_payment_by_meta( '_pronamic_payment_subscription_id', $post_id );

				if ( $payment ) {
					$config_id = get_post_meta( $payment->get_id(), '_pronamic_payment_config_id', true );
				}

				if ( isset( $config_id ) && ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_subscription_description':
				echo esc_html( get_post_meta( $post_id, '_pronamic_subscription_description', true ) );

				break;
			case 'pronamic_subscription_amount':
				$currency = get_post_meta( $post_id, '_pronamic_subscription_currency', true );
				$amount   = get_post_meta( $post_id, '_pronamic_subscription_amount', true );

				echo esc_html( Pronamic_WP_Util::format_price( $amount, $currency ) );

				break;
			case 'pronamic_subscription_interval':
				printf(
					'%s %s %s',
					esc_html_e( 'Every', 'pronamic_ideal' ),
					esc_html( get_post_meta( $post_id, '_pronamic_subscription_interval', true ) ),
					esc_html( get_post_meta( $post_id, '_pronamic_subscription_interval_period', true ) )
				);

				break;
			case 'pronamic_subscription_frequency':
				$frequency = get_post_meta( $post_id, '_pronamic_subscription_frequency', true );

				if ( '' === $frequency ) {
					echo esc_html_x( 'Unlimited', 'Recurring payment', 'pronamic_ideal' );
				} else {
					echo esc_html( sprintf( _n( '%s time', '%s times', $frequency, 'pronamic_ideal' ), $frequency ) );
				}

				break;
			case 'pronamic_subscription_recurring':
				// Interval
				$interval = get_post_meta( $post_id, '_pronamic_subscription_interval', true );
				$period   = get_post_meta( $post_id, '_pronamic_subscription_interval_period', true );

				switch ( $period ) {
					case 'D' :
					case 'day':
					case 'days':
						$period = _n( 'day', 'days', $interval, 'pronamic_ideal' );
						break;

					case 'W' :
					case 'week':
					case 'weeks':
						$period = _n( 'week', 'weeks', $interval, 'pronamic_ideal' );
						break;

					case 'M' :
					case 'month':
					case 'months':
						$period = _n( 'month', 'months', $interval, 'pronamic_ideal' );
						break;

					case 'Y' :
					case 'year':
					case 'years':
						$period = _n( 'year', 'years', $interval, 'pronamic_ideal' );
						break;
				}

				if ( '1' === $interval ) {
					$interval = $period;
				} else {
					$interval = sprintf( '%s %s', $interval, $period );
				}

				// Frequency
				$frequency = get_post_meta( $post_id, '_pronamic_subscription_frequency', true );

				if ( '' === $frequency ) {
					$frequency = _x( 'Forever', 'Recurring payment', 'pronamic_ideal' );
				} else {
					$frequency = sprintf( _n( '%s time', '%s times', $frequency, 'pronamic_ideal' ), $frequency );
				}

				printf( //xss ok
					'%s %s<br>%s',
					esc_html__( 'Every', 'pronamic_ideal' ),
					esc_html( $interval ),
					esc_html( $frequency )
				);

				break;
			case 'pronamic_subscription_status':
				$status_object = get_post_status_object( get_post_status( $post_id ) );

				if ( isset( $status_object, $status_object->label ) ) {
					echo esc_html( $status_object->label );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_subscription_source':
				$payment = get_pronamic_payment_by_meta( '_pronamic_payment_subscription_id', $post_id );

				if ( $payment ) {
					echo $payment->get_source_text(); //xss ok
				} else {
					$source    = get_post_meta( $post_id, '_pronamic_subscription_source', true );
					$source_id = get_post_meta( $post_id, '_pronamic_subscription_source_id', true );

					printf(
						'%s<br />%s', //xss ok
						esc_html( $source ),
						esc_html( $source_id )
					);
				}

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
				'pronamic_subscription',
				__( 'Subscription', 'pronamic_ideal' ),
				array( $this, 'meta_box_info' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_subscription_log',
				__( 'Log', 'pronamic_ideal' ),
				array( $this, 'meta_box_log' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_subscription_payments',
				__( 'Payments', 'pronamic_ideal' ),
				array( $this, 'meta_box_payments' ),
				$post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'pronamic_subscription_update',
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
	 * Pronamic Pay subscription info meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_info( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-subscription-info.php';
	}

	/**
	 * Pronamic Pay subscription log meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_log( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-subscription-log.php';
	}

	/**
	 * Pronamic Pay subscription payments meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_payments( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-subscription-payments.php';
	}

	/**
	 * Pronamic Pay subscription update meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_update( $post ) {
		wp_nonce_field( 'pronamic_subscription_update', 'pronamic_subscription_update_nonce' );

		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-subscription-update.php';
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
			case 'subscr_pending' :
				return Pronamic_WP_Pay_Statuses::OPEN;
			case 'subscr_cancelled' :
				return Pronamic_WP_Pay_Statuses::CANCELLED;
			case 'subscr_expired' :
				return Pronamic_WP_Pay_Statuses::EXPIRED;
			case 'subscr_failed' :
				return Pronamic_WP_Pay_Statuses::FAILURE;
			case 'subscr_active' :
				return Pronamic_WP_Pay_Statuses::ACTIVE;
			case 'subscr_completed' :
				return Pronamic_WP_Pay_Statuses::COMPLETED;
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
			filter_has_var( INPUT_POST, 'pronamic_subscription_update_nonce' )
			&&
			check_admin_referer( 'pronamic_subscription_update', 'pronamic_subscription_update_nonce' )
			&&
			'pronamic_pay_subscr' === get_post_type( $post )
		) {
			$can_redirect = false;

			$old_status_meta = $this->translate_post_status_to_meta_status( $old_status );
			$new_status_meta = $this->translate_post_status_to_meta_status( $new_status );

			$subscription = get_pronamic_subscription( $post->ID );
			$payment = $subscription->get_first_payment();

			do_action( 'pronamic_subscription_status_update_' . $payment->source . '_' . strtolower( $old_status_meta ) . '_to_' . strtolower( $new_status_meta ), $subscription, $payment, $can_redirect );
			do_action( 'pronamic_subscription_status_update_' . $payment->source, $subscription, $payment, $can_redirect );
			do_action( 'pronamic_subscription_status_update', $subscription, $payment, $can_redirect );
		}
	}
}
