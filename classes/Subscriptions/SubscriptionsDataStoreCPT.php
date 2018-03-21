<?php
/**
 * Subscriptions Data Store CPT
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use DateTimeZone;
use Pronamic\WordPress\Pay\AbstractDataStoreCPT;
use Pronamic\WordPress\Pay\DateTime;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Title: Subscriptions data store CPT
 *
 * @see https://woocommerce.com/2017/04/woocommerce-3-0-release/
 * @see https://woocommerce.wordpress.com/2016/10/27/the-new-crud-classes-in-woocommerce-2-7/
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class SubscriptionsDataStoreCPT extends AbstractDataStoreCPT {
	/**
	 * Construct subscriptions data store CPT object.
	 */
	public function __construct() {
		$this->meta_key_prefix = '_pronamic_subscription_';
	}

	/**
	 * Create subscription.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L47-L76
	 * @param Subscription $subscription Create the specified subscription in this data store.
	 */
	public function create( $subscription ) {
		$result = wp_insert_post(
			array(
				'post_type'     => 'pronamic_pay_subscr',
				'post_date_gmt' => $subscription->date->format( 'Y-m-d H:i:s' ),
				'post_title'    => sprintf(
					'Subscription – %s',
					date_i18n( _x( '@todo', 'Subscription title date format parsed by `date_i18n`.', 'pronamic_ideal' ) )
				),
				'post_status'   => $this->get_post_status( $subscription->get_status() ),
				'post_author'   => $subscription->user_id,
			), true
		);

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$subscription->set_id( $result );
		$subscription->post = get_post( $result );

		$this->update_post_meta( $subscription );

		do_action( 'pronamic_pay_new_subscription', $subscription );

		return true;
	}

	/**
	 * Read subscription.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L78-L111
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L81-L136
	 * @param Subscription $subscription The subscription to read the additional data for.
	 */
	public function read( $subscription ) {
		$subscription->post    = get_post( $subscription->get_id() );
		$subscription->title   = get_the_title( $subscription->get_id() );
		$subscription->date    = new DateTime( get_post_field( 'post_date_gmt', $subscription->get_id(), 'raw' ), new DateTimeZone( 'UTC' ) );
		$subscription->user_id = get_post_field( 'post_author', $subscription->get_id(), 'raw' );

		$this->read_post_meta( $subscription );
	}

	/**
	 * Update subscription.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L113-L154
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Subscription $subscription The subscription to update in this data store.
	 */
	public function update( $subscription ) {
		$data = array(
			'ID' => $subscription->get_id(),
		);

		$post_status = $this->get_post_status( $subscription->get_status(), null );

		if ( null !== $post_status ) {
			$data['post_status'] = $post_status;
		}

		wp_update_post( $data );

		$this->update_post_meta( $subscription );
	}

	/**
	 * Get post status.
	 *
	 * @param string $meta_status The subscription meta status to get the post status for.
	 * @param string $default     The deafult post status if the meta status could not be converted to a post status.
	 * @return string
	 */
	public function get_post_status( $meta_status, $default = 'subscr_pending' ) {
		switch ( $meta_status ) {
			case Statuses::CANCELLED:
				return 'subscr_cancelled';
			case Statuses::EXPIRED:
				return 'subscr_expired';
			case Statuses::FAILURE:
				return 'subscr_failed';
			case Statuses::SUCCESS:
				return 'subscr_active';
			case Statuses::OPEN:
				return 'subscr_pending';
			case Statuses::COMPLETED:
				return 'subscr_completed';
			default:
				return $default;
		}
	}

	/**
	 * Get meta status label.
	 *
	 * @param string $meta_status The subscription meta status to get the status label for.
	 * @return string|boolean
	 */
	public function get_meta_status_label( $meta_status ) {
		$post_status = $this->get_post_status( $meta_status, null );

		if ( empty( $post_status ) ) {
			return false;
		}

		$status_object = get_post_status_object( $post_status );

		if ( isset( $status_object, $status_object->label ) ) {
			return $status_object->label;
		}

		return false;
	}

	/**
	 * Read post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/abstracts/abstract-wc-data.php#L462-L507
	 * @param Subscription $subscription The subscription to read the post meta for.
	 */
	private function read_post_meta( $subscription ) {
		$id = $subscription->get_id();

		$subscription->config_id       = $this->get_meta( $id, 'config_id' );
		$subscription->key             = $this->get_meta( $id, 'key' );
		$subscription->source          = $this->get_meta( $id, 'source' );
		$subscription->source_id       = $this->get_meta( $id, 'source_id' );
		$subscription->frequency       = $this->get_meta( $id, 'frequency' );
		$subscription->interval        = $this->get_meta( $id, 'interval' );
		$subscription->interval_period = $this->get_meta( $id, 'interval_period' );
		$subscription->currency        = $this->get_meta( $id, 'currency' );
		$subscription->amount          = $this->get_meta( $id, 'amount' );
		$subscription->transaction_id  = $this->get_meta( $id, 'transaction_id' );
		$subscription->status          = $this->get_meta( $id, 'status' );
		$subscription->description     = $this->get_meta( $id, 'description' );
		$subscription->email           = $this->get_meta( $id, 'email' );
		$subscription->customer_name   = $this->get_meta( $id, 'customer_name' );
		$subscription->payment_method  = $this->get_meta( $id, 'payment_method' );

		$first_payment = $subscription->get_first_payment();

		if ( is_object( $first_payment ) ) {
			if ( empty( $subscription->config_id ) ) {
				$subscription->config_id = $first_payment->config_id;
			}

			if ( empty( $subscription->user_id ) ) {
				$subscription->user_id = $first_payment->user_id;
			}

			if ( empty( $subscription->payment_method ) ) {
				$subscription->payment_method = $first_payment->method;
			}
		}

		// Start Date.
		$start_date = $this->get_meta_date( $id, 'start_date' );

		if ( empty( $start_date ) ) {
			// If no meta start date is set, use subscription date.
			$start_date = clone $subscription->date;
		}

		$subscription->start_date = $start_date;

		// Expiry Date.
		$expiry_date = $this->get_meta_date( $id, 'expiry_date' );

		if ( empty( $expiry_date ) ) {
			// If no meta expiry date is set, use start date + 1 interval period.
			$expiry_date = clone $start_date;

			$expiry_date->add( $subscription->get_date_interval() );
		}

		$subscription->expiry_date = $expiry_date;

		// First Payment Date.
		$first_payment = $this->get_meta_date( $id, 'first_payment' );

		if ( empty( $first_payment ) ) {
			// If no meta first payment date is set, use subscription date.
			$first_payment = clone $subscription->date;
		}

		$subscription->first_payment = $first_payment;

		// Next Payment Date.
		$subscription->next_payment = $this->get_meta_date( $id, 'next_payment' );

		// Final Payment Date.
		$subscription->final_payment = $this->get_meta_date( $id, 'final_payment' );

		// Renewal Notice.
		$subscription->renewal_notice = $this->get_meta_date( $id, 'renewal_notice' );
	}

	/**
	 * Update payment post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Subscription $subscription The subscription to update the post meta for.
	 */
	private function update_post_meta( $subscription ) {
		$id = $subscription->get_id();

		$previous_status = $this->get_meta( $id, 'status' );
		$previous_status = strtolower( $previous_status );
		$previous_status = empty( $previous_status ) ? 'unknown' : $previous_status;

		$this->update_meta( $id, 'config_id', $subscription->config_id );
		$this->update_meta( $id, 'key', $subscription->key );
		$this->update_meta( $id, 'source', $subscription->source );
		$this->update_meta( $id, 'source_id', $subscription->source_id );
		$this->update_meta( $id, 'frequency', $subscription->frequency );
		$this->update_meta( $id, 'interval', $subscription->interval );
		$this->update_meta( $id, 'interval_period', $subscription->interval_period );
		$this->update_meta( $id, 'currency', $subscription->currency );
		$this->update_meta( $id, 'amount', $subscription->amount );
		$this->update_meta( $id, 'transaction_id', $subscription->transaction_id );
		$this->update_meta( $id, 'status', $subscription->status );
		$this->update_meta( $id, 'description', $subscription->description );
		$this->update_meta( $id, 'email', $subscription->email );
		$this->update_meta( $id, 'customer_name', $subscription->customer_name );
		$this->update_meta( $id, 'payment_method', $subscription->payment_method );
		$this->update_meta( $id, 'start_date', $subscription->start_date );
		$this->update_meta( $id, 'expiry_date', $subscription->expiry_date );
		$this->update_meta( $id, 'first_payment', $subscription->first_payment );
		$this->update_meta( $id, 'next_payment', $subscription->next_payment );
		$this->update_meta( $id, 'final_payment', $subscription->final_payment );
		$this->update_meta( $id, 'renewal_notice', $subscription->renewal_notice );
		$this->update_meta( $id, 'start_date', $subscription->start_date );
		$this->update_meta( $id, 'expiry_date', $subscription->expiry_date );
		$this->update_meta( $id, 'first_payment', $subscription->first_payment );
		$this->update_meta( $id, 'next_payment', $subscription->next_payment );
		$this->update_meta( $id, 'final_payment', $subscription->final_payment );
		$this->update_meta( $id, 'renewal_notice', $subscription->renewal_notice );

		if ( $previous_status !== $subscription->status ) {
			$can_redirect = false;

			do_action( 'pronamic_subscription_status_update_' . $subscription->source . '_' . strtolower( $previous_status ) . '_to_' . strtolower( $subscription->status ), $subscription, $can_redirect );
			do_action( 'pronamic_subscription_status_update_' . $subscription->source, $subscription, $can_redirect );
			do_action( 'pronamic_subscription_status_update', $subscription, $can_redirect );
		}
	}
}
