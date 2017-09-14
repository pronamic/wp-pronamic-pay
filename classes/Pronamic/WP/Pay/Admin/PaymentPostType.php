<?php

/**
 * Title: WordPress admin payment post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.4.2
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_PaymentPostType {
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_payment';

	/**
	 * Admin notices.
	 */
	private $admin_notices = array();

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin payment post type object
	 */
	public function __construct() {
		add_filter( 'request', array( $this, 'request' ) );

		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'columns' ) );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_sortable_columns', array( $this, 'sortable_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'load-post.php', array( $this, 'maybe_check_status' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		add_filter( 'default_hidden_columns', array( $this, 'default_hidden_columns' ) );

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

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
	 * Maybe check status.
	 */
	public function maybe_check_status() {
		// Current user
		if ( ! current_user_can( 'edit_payments' ) ) {
			return;
		}

		// Screen
		$screen = get_current_screen();

		if ( ! ( 'post' === $screen->base && 'pronamic_payment' === $screen->post_type ) ) {
			return;
		}

		$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		if ( filter_has_var( INPUT_GET, 'pronamic_pay_check_status' ) && check_admin_referer( 'pronamic_payment_check_status_' . $post_id ) ) {
			$payment = get_pronamic_payment( $post_id );

			Pronamic_WP_Pay_Plugin::update_payment( $payment, false );

			$this->admin_notices[] = array(
				'type'    => 'info',
				'message' => __( 'Payment status updated.', 'pronamic_ideal' ),
			);
		}
	}

	public function admin_notices() {
		foreach ( $this->admin_notices as $notice ) {
			printf(
				'<div class="notice notice-%1$s"><p>%2$s</p></div>',
				esc_attr( $notice['type'] ),
				esc_html( $notice['message'] )
			);
		}
	}

	/**
	 * Pre get posts.
	 *
	 * @param WP_Query $query
	 */
	public function pre_get_posts( $query ) {
		if ( 'pronamic_payment_amount' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_pronamic_payment_amount' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	//////////////////////////////////////////////////

	public function columns( $columns ) {
		$columns = array(
			'cb'                            => '<input type="checkbox" />',
			'pronamic_payment_status'       => sprintf(
				'<span class="pronamic-pay-tip pronamic-pay-icon" data-tip="%s">%s</span>',
				esc_html__( 'Status', 'pronamic_ideal' ),
				esc_html__( 'Status', 'pronamic_ideal' )
			),
			'pronamic_payment_subscription' => sprintf(
				'<span class="pronamic-pay-tip pronamic-pay-icon pronamic-pay-icon-recurring" data-tip="%s">%s</span>',
				esc_html__( 'Subscription', 'pronamic_ideal' ),
				esc_html__( 'Subscription', 'pronamic_ideal' )
			),
			'pronamic_payment_title'        => __( 'Payment', 'pronamic_ideal' ),
			'pronamic_payment_transaction'  => __( 'Transaction', 'pronamic_ideal' ),
			'pronamic_payment_gateway'      => __( 'Gateway', 'pronamic_ideal' ),
			'pronamic_payment_description'  => __( 'Description', 'pronamic_ideal' ),
			'pronamic_payment_customer'     => __( 'Customer', 'pronamic_ideal' ),
			'pronamic_payment_amount'       => __( 'Amount', 'pronamic_ideal' ),
			'pronamic_payment_date'         => __( 'Date', 'pronamic_ideal' ),
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
		$payment = get_pronamic_payment( $post_id );

		switch ( $column ) {
			case 'pronamic_payment_status' :
				$post_status = get_post_status( $post_id );

				$label = __( 'Unknown', 'pronamic_ideal' );

				if ( 'trash' === $post_status ) {
					$post_status = get_post_meta( $post_id, '_wp_trash_meta_status', true );
				}

				$status_object = get_post_status_object( $post_status );

				if ( isset( $status_object, $status_object->label ) ) {
					$label = $status_object->label;
				}

				printf(
					'<span class="pronamic-pay-tip pronamic-pay-icon %s" data-tip="%s">%s</span>',
					esc_attr( Pronamic_WP_Pay_Admin::get_post_status_icon_class( $post_status ) ),
					esc_attr( $label ),
					esc_html( $label )
				);

				break;
			case 'pronamic_payment_subscription' :
				$subscription_id = get_post_meta( $post_id, '_pronamic_payment_subscription_id', true );

				if ( $subscription_id ) {
					$label = __( 'Recurring payment', 'pronamic_ideal' );
					$class = 'pronamic-pay-icon-recurring';

					$recurring = get_post_meta( $post_id, '_pronamic_payment_recurring', true );

					if ( ! $recurring ) {
						$label = __( 'First of recurring payment', 'pronamic_ideal' );
						$class = ' pronamic-pay-icon-recurring-first';
					}

					printf(
						'<span class="pronamic-pay-tip pronamic-pay-icon %s" data-tip="%s">%s</span>',
						esc_attr( $class ),
						esc_attr( $label ),
						esc_attr( $label )
					);
				}

				break;
			case 'pronamic_payment_title' :
				$source             = $payment->get_source();
				$source_id          = $payment->get_source_id();
				$source_description = $payment->get_source_description();

				$source_id_text = '#' . $source_id;

				$source_link = $payment->get_source_link();

				if ( null !== $source_link ) {
					$source_id_text = sprintf(
						'<a href="%s">%s</a>',
						esc_url( $source_link ),
						$source_id_text
					);
				}

				echo wp_kses(
					sprintf(
						__( '%1$s for %2$s %3$s', 'pronamic_ideal' ),
						sprintf(
							'<a href="%s" class="row-title"><strong>#%s</strong></a>',
							esc_url( get_edit_post_link( $post_id ) ),
							esc_html( $post_id )
						),
						$source_description,
						$source_id_text
					),
					array(
						'a'      => array(
							'href'  => true,
							'class' => true,
						),
						'strong' => array(),
					)
				);

				break;
			case 'pronamic_payment_gateway' :
				$config_id = get_post_meta( $post_id, '_pronamic_payment_config_id', true );

				if ( ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_payment_transaction' :
				$transaction_id = get_post_meta( $post_id, '_pronamic_payment_transaction_id', true );

				$url = $payment->get_provider_link();

				if ( empty( $url ) ) {
					echo esc_html( $transaction_id );
				} else {
					printf(
						'<a href="%s">%s</a>',
						esc_url( $url ),
						esc_html( $transaction_id )
					);
				}

				break;
			case 'pronamic_payment_description' :
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_description', true ) );

				break;
			case 'pronamic_payment_amount' :
				$currency = get_post_meta( $post_id, '_pronamic_payment_currency', true );
				$amount   = get_post_meta( $post_id, '_pronamic_payment_amount', true );

				echo esc_html( Pronamic_WP_Util::format_price( $amount, $currency ) );

				break;
			case 'pronamic_payment_date' :
				echo esc_html( get_the_time( __( 'D j M Y \a\t H:i', 'pronamic_ideal' ), $post_id ) );

				break;
			case 'pronamic_payment_consumer' :
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_name', true ) );
				echo '<br />';
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_account_number', true ) );
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true ) );
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true ) );
				echo '<br />';
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_city', true ) );

				break;
			case 'pronamic_payment_customer' :
				echo esc_html( get_post_meta( $post_id, '_pronamic_payment_customer_name', true ) );

				break;
			case 'pronamic_payment_source' :
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
	 * Post updated messages.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type
	 * @see https://github.com/WordPress/WordPress/blob/4.4.2/wp-admin/edit-form-advanced.php#L134-L173
	 * @see https://github.com/woothemes/woocommerce/blob/2.5.5/includes/admin/class-wc-admin-post-types.php#L111-L168
	 * @param string $message
	 * @return string
	 */
	public function post_updated_messages( $messages ) {
		global $post;

		// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352797&filters[translation_id]=37948900
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i', 'pronamic_ideal' ), strtotime( $post->post_date ) );

		$messages[ self::POST_TYPE ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Payment updated.', 'pronamic_ideal' ),
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352799&filters[translation_id]=37947229
			2  => $messages['post'][2],
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352800&filters[translation_id]=37947870
			3  => $messages['post'][3],
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352798&filters[translation_id]=37947230
			4  => __( 'Payment updated.', 'pronamic_ideal' ),
			/* translators: %s: date and time of the revision */
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352801&filters[translation_id]=37947231
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Payment restored to revision from %s.', 'pronamic_ideal' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // WPCS: CSRF ok.
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352802&filters[translation_id]=37949178
			6  => __( 'Payment published.', 'pronamic_ideal' ),
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352803&filters[translation_id]=37947232
			7  => __( 'Payment saved.', 'pronamic_ideal' ),
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352804&filters[translation_id]=37949303
			8  => __( 'Payment submitted.', 'pronamic_ideal' ),
			// @see https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352805&filters[translation_id]=37949302
			9  => sprintf( __( 'Payment scheduled for: %s.', 'pronamic_ideal' ), '<strong>' . $scheduled_date . '</strong>' ),
			// @https://translate.wordpress.org/projects/wp/4.4.x/admin/nl/default?filters[status]=either&filters[original_id]=2352806&filters[translation_id]=37949301
			10 => __( 'Payment draft updated.', 'pronamic_ideal' ),
		);

		return $messages;
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
			'edit_others_posts'      => 'edit_others_payments',
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
