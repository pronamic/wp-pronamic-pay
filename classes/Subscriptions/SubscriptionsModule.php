<?php
/**
 * Subscriptions Module
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use DateInterval;
use DatePeriod;
use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Core\Gateway;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;
use WP_CLI;
use WP_Query;

/**
 * Title: Subscriptions module
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @see https://woocommerce.com/2017/04/woocommerce-3-0-release/
 * @see https://woocommerce.wordpress.com/2016/10/27/the-new-crud-classes-in-woocommerce-2-7/
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class SubscriptionsModule {
	/**
	 * Plugin.
	 *
	 * @var Plugin $plugin
	 */
	public $plugin;

	/**
	 * Construct and initialize a subscriptions module object.
	 *
	 * @param Plugin $plugin The plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'wp_loaded', array( $this, 'handle_subscription' ) );

		add_action( 'plugins_loaded', array( $this, 'maybe_schedule_subscription_payments' ), 5 );

		// Exclude payment and subscription notes.
		add_filter( 'comments_clauses', array( $this, 'exclude_subscription_comment_notes' ), 10, 2 );

		add_action( 'pronamic_pay_new_payment', array( $this, 'maybe_create_subscription' ) );

		// The 'pronamic_pay_update_subscription_payments' hook adds subscription payments and sends renewal notices.
		add_action( 'pronamic_pay_update_subscription_payments', array( $this, 'update_subscription_payments' ) );

		// Listen to payment status changes so we can update related subscriptions.
		add_action( 'pronamic_payment_status_update', array( $this, 'payment_status_update' ) );

		// WordPress CLI.
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.1/includes/class-woocommerce.php#L365-L369.
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.1/includes/class-wc-cli.php.
		// @see https://make.wordpress.org/cli/handbook/commands-cookbook/.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'pay subscriptions test', array( $this, 'cli_subscriptions_test' ) );
		}
	}

	/**
	 * Handle subscription actions.
	 *
	 * Extensions like Gravity Forms can send action links in for example
	 * email notifications so users can cancel or renew their subscription.
	 */
	public function handle_subscription() {
		if ( ! filter_has_var( INPUT_GET, 'subscription' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'action' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'key' ) ) {
			return;
		}

		$subscription_id = filter_input( INPUT_GET, 'subscription', FILTER_SANITIZE_STRING );
		$subscription    = get_pronamic_subscription( $subscription_id );

		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

		// Check if subscription is valid.
		if ( ! $subscription ) {
			return;
		}

		// Check if subscription key is valid.
		if ( $key !== $subscription->get_key() ) {
			wp_redirect( home_url() );

			exit;
		}

		// Check if we should redirect.
		$should_redirect = true;

		switch ( $action ) {
			case 'cancel':
				if ( Statuses::CANCELLED !== $subscription->get_status() ) {
					$subscription->set_status( Statuses::CANCELLED );

					$this->update_subscription( $subscription, $should_redirect );
				}

				break;
			case 'renew':
				$gateway = Plugin::get_gateway( $subscription->config_id );

				if ( Statuses::SUCCESS !== $subscription->get_status() ) {
					$payment = $this->start_recurring( $subscription, $gateway, true );

					if ( ! $gateway->has_error() ) {
						// Redirect.
						$gateway->redirect( $payment );
					}
				}

				wp_redirect( home_url() );

				exit;
		}
	}

	/**
	 * Start a recurring payment at the specified gateway for the specified subscription.
	 *
	 * @param Subscription $subscription The subscription to start a recurring payment for.
	 * @param Gateway      $gateway      The gateway to start the recurring payment at.
	 * @param boolean      $renewal      Flag for renewal payment.
	 */
	public function start_recurring( Subscription $subscription, Gateway $gateway, $renewal = false ) {
		// If next payment date is after the subscription end date unset the next payment date.
		if ( isset( $subscription->end_date, $subscription->next_payment ) && $subscription->end_date <= $subscription->next_payment ) {
			$subscription->next_payment = null;
		}

		// If there is no next payment date change the subscription status to completed.
		if ( empty( $subscription->next_payment ) ) {
			$subscription->status      = Statuses::COMPLETED;
			$subscription->expiry_date = $subscription->end_date;

			$result = $this->plugin->subscriptions_data_store->update( $subscription );

			// @todo
			return;
		}

		// Calculate payment start and end dates.
		$start_date = clone $subscription->next_payment;

		$end_date = clone $start_date;
		$end_date->add( $subscription->get_date_interval() );

		$subscription->next_payment = $end_date;

		// Create follow up payment.
		$payment = new Payment();

		$payment->config_id        = $subscription->config_id;
		$payment->user_id          = $subscription->user_id;
		$payment->source           = $subscription->source;
		$payment->source_id        = $subscription->source_id;
		$payment->description      = $subscription->description;
		$payment->order_id         = $subscription->order_id;
		$payment->currency         = $subscription->currency;
		$payment->email            = $subscription->email;
		$payment->customer_name    = $subscription->customer_name;
		$payment->address          = $subscription->address;
		$payment->address          = $subscription->address;
		$payment->city             = $subscription->city;
		$payment->zip              = $subscription->zip;
		$payment->country          = $subscription->country;
		$payment->telephone_number = $subscription->telephone_number;
		$payment->method           = $subscription->payment_method;
		$payment->subscription     = $subscription;
		$payment->subscription_id  = $subscription->get_id();
		$payment->amount           = $subscription->amount;
		$payment->start_date       = $start_date;
		$payment->end_date         = $end_date;
		$payment->recurring_type   = 'recurring';
		$payment->recurring        = ! $renewal;

		// Start payment.
		$payment = Plugin::start_payment( $payment, $gateway );

		// Update subscription.
		$result = $this->plugin->subscriptions_data_store->update( $subscription );

		return $payment;
	}

	/**
	 * Update the specified subscription and redirect if allowed.
	 *
	 * @param Subscription $subscription The updated subscription.
	 * @param boolean      $can_redirect Flag to redirect or not.
	 */
	public function update_subscription( $subscription = null, $can_redirect = true ) {
		if ( empty( $subscription ) ) {
			return;
		}

		$this->plugin->subscriptions_data_store->update( $subscription );

		if ( defined( 'DOING_CRON' ) && empty( $subscription->status ) ) {
			$can_redirect = false;
		}

		if ( $can_redirect ) {
			wp_redirect( home_url() );

			exit;
		}
	}

	/**
	 * Comments clauses.
	 *
	 * @param array            $clauses The database query clauses.
	 * @param WP_Comment_Query $query   The WordPress comment query object.
	 * @return array
	 */
	public function exclude_subscription_comment_notes( $clauses, $query ) {
		$type = $query->query_vars['type'];

		// Ignore subscription notes comments if it's not specifically requested.
		if ( 'subscription_note' !== $type ) {
			$clauses['where'] .= " AND comment_type != 'subscription_note'";
		}

		return $clauses;
	}

	/**
	 * Maybe schedule subscription payments.
	 */
	public function maybe_schedule_subscription_payments() {
		if ( wp_next_scheduled( 'pronamic_pay_update_subscription_payments' ) ) {
			return;
		}

		wp_schedule_event( time(), 'hourly', 'pronamic_pay_update_subscription_payments' );
	}

	/**
	 * Maybe create subscription for the specified payment.
	 *
	 * @param Payment $payment The new payment.
	 */
	public function maybe_create_subscription( $payment ) {
		// Check if there is already subscription attached to the payment.
		$subscription_id = $payment->get_subscription_id();

		if ( ! empty( $subscription_id ) ) {
			// Subscription already created.
			return;
		}

		// Check if there is a subscription object attached to the payment.
		$subscription_data = $payment->subscription;

		if ( empty( $subscription_data ) ) {
			return;
		}

		// New subscription.
		$subscription = new Subscription();

		$subscription->config_id       = $payment->config_id;
		$subscription->user_id         = $payment->user_id;
		$subscription->title           = sprintf( __( 'Subscription for %s', 'pronamic_ideal' ), $payment->title );
		$subscription->frequency       = $subscription_data->get_frequency();
		$subscription->interval        = $subscription_data->get_interval();
		$subscription->interval_period = $subscription_data->get_interval_period();
		$subscription->currency        = $subscription_data->get_currency();
		$subscription->amount          = $subscription_data->get_amount();
		$subscription->key             = uniqid( 'subscr_' );
		$subscription->source          = $payment->source;
		$subscription->source_id       = $payment->source_id;
		$subscription->description     = $payment->description;
		$subscription->email           = $payment->email;
		$subscription->customer_name   = $payment->customer_name;
		$subscription->payment_method  = $payment->method;
		$subscription->first_payment   = $payment->date;

		// @todo
		// Calculate dates
		// @see https://github.com/pronamic/wp-pronamic-ideal/blob/4.7.0/classes/Pronamic/WP/Pay/Plugin.php#L883-L964
		$interval = $subscription->get_date_interval();

		$start_date  = clone $payment->date;
		$expiry_date = clone $start_date;

		$next_date = clone $start_date;
		$next_date->add( $interval );

		$end_date = null;

		if ( $subscription_data->frequency ) {
			// @see https://stackoverflow.com/a/10818981/6411283
			$period = new DatePeriod( $start_date, $interval, $subscription_data->frequency );

			$dates = iterator_to_array( $period );

			$end_date = end( $dates );
		}

		$subscription->start_date   = $start_date;
		$subscription->end_date     = $end_date;
		$subscription->expiry_date  = $expiry_date;
		$subscription->next_payment = $next_date;

		// Create.
		$result = $this->plugin->subscriptions_data_store->create( $subscription );

		if ( $result ) {
			$payment->subscription_id = $subscription->get_id();

			$payment->recurring_type = 'first';
			$payment->start_date     = $start_date;
			$payment->end_date       = $next_date;

			$this->plugin->payments_data_store->update( $payment );
		}
	}

	/**
	 * Get expiring subscriptions.
	 *
	 * @see https://github.com/wp-premium/edd-software-licensing/blob/3.5.23/includes/license-renewals.php#L715-L746
	 * @see https://github.com/wp-premium/edd-software-licensing/blob/3.5.23/includes/license-renewals.php#L652-L712
	 *
	 * @param DateTime $start_date The start date of the period to check for expiring subscriptions.
	 * @param DateTime $end_date   The end date of the period to check for expiring subscriptions.
	 * @return array
	 */
	public function get_expiring_subscription_posts( DateTime $start_date, DateTime $end_date ) {
		$args = array(
			'post_type'   => 'pronamic_pay_subscr',
			'nopaging'    => true,
			'orderby'     => 'post_date',
			'order'       => 'ASC',
			'post_status' => array(
				'subscr_pending',
				'subscr_expired',
				'subscr_failed',
				'subscr_active',
			),
			'meta_query'  => array(
				array(
					'key'     => '_pronamic_subscription_expiry_date',
					'value'   => array(
						$start_date->format( DateTime::MYSQL ),
						$end_date->format( DateTime::MYSQL ),
					),
					'compare' => 'BETWEEN',
					'type'    => 'DATETIME',
				),
			),
		);

		$query = new WP_Query( $args );

		return $query->posts;
	}

	/**
	 * Update subscription payments.
	 */
	public function update_subscription_payments() {
		$this->send_subscription_renewal_notices();

		// Don't create payments for sources which schedule payments.
		$sources = array(
			'woocommerce',
		);

		$args = array(
			'post_type'   => 'pronamic_pay_subscr',
			'nopaging'    => true,
			'orderby'     => 'post_date',
			'order'       => 'ASC',
			'post_status' => array(
				'subscr_pending',
				'subscr_expired',
				'subscr_failed',
				'subscr_active',
			),
			'meta_query'  => array(
				array(
					'key'     => '_pronamic_subscription_source',
					'compare' => 'NOT IN',
					'value'   => $sources,
				),
				array(
					'key'     => '_pronamic_subscription_next_payment',
					'compare' => '<=',
					'value'   => current_time( 'mysql', true ),
					'type'    => 'DATETIME',
				),
			),
		);

		$query = new WP_Query( $args );

		foreach ( $query->posts as $post ) {
			$subscription = new Subscription( $post->ID );

			$gateway = Plugin::get_gateway( $subscription->config_id );

			$payment = $this->start_recurring( $subscription, $gateway );

			if ( $payment ) {
				Plugin::update_payment( $payment, false );
			}
		}
	}

	/**
	 * Send renewal notices.
	 *
	 * @see https://github.com/wp-premium/edd-software-licensing/blob/3.5.23/includes/license-renewals.php#L652-L712
	 * @see https://github.com/wp-premium/edd-software-licensing/blob/3.5.23/includes/license-renewals.php#L715-L746
	 * @see https://github.com/wp-premium/edd-software-licensing/blob/3.5.23/includes/classes/class-sl-emails.php#L41-L126
	 */
	public function send_subscription_renewal_notices() {
		$interval = new DateInterval( 'P1W' ); // 1 week

		$start_date = new DateTime( 'midnight', new DateTimeZone( 'UTC' ) );

		$end_date = clone $start_date;
		$end_date->add( $interval );

		$expiring_subscription_posts = $this->get_expiring_subscription_posts( $start_date, $end_date );

		foreach ( $expiring_subscription_posts as $post ) {
			$subscription = new Subscription( $post->ID );

			$expiry_date = $subscription->get_expiry_date();

			$sent_date_string = get_post_meta( $post->ID, '_pronamic_subscription_renewal_sent_1week', true );

			if ( $sent_date_string ) {
				$first_date = clone $expiry_date;
				$first_date->sub( $interval );

				$sent_date = new DateTime( $sent_date_string, new DateTimeZone( 'UTC' ) );

				if ( $sent_date > $first_date ) {
					// Prevent renewal notices from being sent more than once.
					continue;
				}

				delete_post_meta( $post->ID, '_pronamic_subscription_renewal_sent_1week' );
			}

			do_action( 'pronamic_subscription_renewal_notice_' . $subscription->get_source(), $subscription );

			update_post_meta( $post->ID, '_pronamic_subscription_renewal_sent_1week', $start_date->format( DateTime::MYSQL ) );
		}
	}

	/**
	 * Payment status update.
	 *
	 * @param Payment $payment The status updated payment.
	 */
	public function payment_status_update( $payment ) {
		// Check if the payment is connected to a subscription.
		$subscription = $payment->get_subscription();

		if ( empty( $subscription ) ) {
			// Payment not connected to a subscription, nothing to do.
			return;
		}

		if ( Statuses::CANCELLED === $subscription->get_status() ) {
			// If subscritpion is cancelled never change the subscription status.
			return;
		}

		// Status.
		$status_before = $subscription->get_status();

		switch ( $payment->get_status() ) {
			case Statuses::OPEN:
				// @todo
				break;
			case Statuses::SUCCESS:
				$subscription->set_status( Statuses::ACTIVE );

				if ( isset( $subscription->expiry_date, $payment->end_date ) && $subscription->expiry_date < $payment->end_date ) {
					$subscription->expiry_date = clone $payment->end_date;
				}

				break;
			case Statuses::FAILURE:
				$subscription->set_status( Statuses::CANCELLED );

				break;
			case Statuses::CANCELLED:
				$subscription->set_status( Statuses::CANCELLED );

				break;
			case Statuses::EXPIRED:
				$subscription->set_status( Statuses::CANCELLED );

				break;
			case Statuses::COMPLETED:
				$subscription->set_status( Statuses::COMPLETED );

				break;
		}

		$status_after = $subscription->get_status();

		if ( $status_before !== $status_after ) {
			$subscription->add_note( sprintf(
				__( 'Subscription status changed from "%1$s" to "%2$s".', 'pronamic_ideal' ),
				esc_html( $this->plugin->subscriptions_data_store->get_meta_status_label( $status_before ) ),
				esc_html( $this->plugin->subscriptions_data_store->get_meta_status_label( $status_after ) )
			) );
		}

		// Update.
		$result = $this->plugin->subscriptions_data_store->update( $subscription );
	}

	/**
	 * CLI subscriptions test.
	 */
	public function cli_subscriptions_test() {
		$args = array(
			'post_type'   => 'pronamic_pay_subscr',
			'nopaging'    => true,
			'orderby'     => 'post_date',
			'order'       => 'ASC',
			'post_status' => array(
				'subscr_pending',
				'subscr_expired',
				'subscr_failed',
				'subscr_active',
			),
			'meta_query'  => array(
				array(
					'key'     => '_pronamic_subscription_source',
					'compare' => 'NOT IN',
					'value'   => array(
						// Don't create payments for sources which schedule payments.
						'woocommerce',
					),
				),
			),
		);

		$query = new WP_Query( $args );

		foreach ( $query->posts as $post ) {
			WP_CLI::log( sprintf( 'Processing post `%d` - "%s"…', $post->ID, get_the_title( $post ) ) );

			$subscription = new Subscription( $post->ID );

			$gateway = Plugin::get_gateway( $subscription->config_id );

			$payment = $this->start_recurring( $subscription, $gateway );

			if ( $payment ) {
				Plugin::update_payment( $payment, false );
			}
		}

		WP_CLI::success( 'Pronamic Pay subscriptions test.' );
	}
}
