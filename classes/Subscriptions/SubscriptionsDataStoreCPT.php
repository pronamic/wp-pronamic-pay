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

use DateTime;
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
class SubscriptionsDataStoreCPT {
	/**
	 * Create subscription.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L47-L76
	 * @param Payment $payment
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
				'post_status'   => $this->get_post_status( $subscription ),
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
	 * @param Subscription $subscription
	 */
	public function read( $subscription ) {
		$subscription->post    = get_post( $subscription->get_id() );
		$subscription->title   = get_the_title( $subscription->get_id() );
		$subscription->date    = new \DateTime( get_post_field( 'post_date_gmt', $subscription->get_id(), 'raw' ) );
		$subscription->user_id = get_post_field( 'post_author', $subscription->get_id(), 'raw' );

		$this->read_post_meta( $subscription );
	}

	/**
	 * Update subscription.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L113-L154
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Subscription $subscription
	 */
	public function update( $subscription ) {
		$data = array(
			'ID' => $subscription->get_id(),
		);

		$post_status = $this->get_post_status( $subscription, null );

		if ( null !== $post_status ) {
			$data['post_status'] = $post_status;
		}

		wp_update_post( $data );

		$this->update_post_meta( $subscription );
	}

	/**
	 * Get post status.
	 *
	 * @param Subscription $subscription
	 * @return string
	 */
	private function get_post_status( $subscription, $default = 'subscr_pending' ) {
		switch ( $subscription->status ) {
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
	 * Read post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/abstracts/abstract-wc-data.php#L462-L507
	 * @param Subscription $subscription
	 */
	private function read_post_meta( $subscription ) {
		$prefix = '_pronamic_subscription_';

		$id = $subscription->get_id();

		$subscription->config_id       = get_post_meta( $id, $prefix . 'config_id', true );
		$subscription->key             = get_post_meta( $id, $prefix . 'key', true );
		$subscription->source          = get_post_meta( $id, $prefix . 'source', true );
		$subscription->source_id       = get_post_meta( $id, $prefix . 'source_id', true );
		$subscription->frequency       = get_post_meta( $id, $prefix . 'frequency', true );
		$subscription->interval        = get_post_meta( $id, $prefix . 'interval', true );
		$subscription->interval_period = get_post_meta( $id, $prefix . 'interval_period', true );
		$subscription->currency        = get_post_meta( $id, $prefix . 'currency', true );
		$subscription->amount          = get_post_meta( $id, $prefix . 'amount', true );
		$subscription->transaction_id  = get_post_meta( $id, $prefix . 'transaction_id', true );
		$subscription->status          = get_post_meta( $id, $prefix . 'status', true );
		$subscription->description     = get_post_meta( $id, $prefix . 'description', true );
		$subscription->email           = get_post_meta( $id, $prefix . 'email', true );
		$subscription->customer_name   = get_post_meta( $id, $prefix . 'customer_name', true );
		$subscription->payment_method  = get_post_meta( $id, $prefix . 'payment_method', true );

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

		$date_string              = get_post_meta( $id, $prefix . 'start_date', true );
		$subscription->start_date = empty( $date_string ) ? null : new DateTime( $date_string );

		$date_string               = get_post_meta( $id, $prefix . 'expiry_date', true );
		$subscription->expiry_date = empty( $date_string ) ? null : new DateTime( $date_string );

		$date_string                 = get_post_meta( $id, $prefix . 'first_payment', true );
		$subscription->first_payment = empty( $date_string ) ? null : new DateTime( $date_string );

		$date_string                = get_post_meta( $id, $prefix . 'next_payment', true );
		$subscription->next_payment = empty( $date_string ) ? null : new DateTime( $date_string );

		$date_string                 = get_post_meta( $id, $prefix . 'final_payment', true );
		$subscription->final_payment = empty( $date_string ) ? null : new DateTime( $date_string );

		$date_string                  = get_post_meta( $id, $prefix . 'renewal_notice', true );
		$subscription->renewal_notice = empty( $date_string ) ? null : new DateTime( $date_string );
	}

	/**
	 * Update payment post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Subscription $subscription
	 */
	private function update_post_meta( $subscription ) {
		$prefix = '_pronamic_subscription_';

		$id = $subscription->get_id();

		$previous_status = get_post_meta( $id, '_pronamic_subscription_status', true );
		$previous_status = strtolower( $previous_status );
		$previous_status = empty( $previous_status ) ? 'unknown' : $previous_status;

		$data = array(
			'config_id'       => $subscription->config_id,
			'key'             => $subscription->key,
			'source'          => $subscription->source,
			'source_id'       => $subscription->source_id,
			'frequency'       => $subscription->frequency,
			'interval'        => $subscription->interval,
			'interval_period' => $subscription->interval_period,
			'currency'        => $subscription->currency,
			'amount'          => $subscription->amount,
			'transaction_id'  => $subscription->transaction_id,
			'status'          => $subscription->status,
			'description'     => $subscription->description,
			'email'           => $subscription->email,
			'customer_name'   => $subscription->customer_name,
			'payment_method'  => $subscription->payment_method,
			'start_date'      => $subscription->start_date,
			'expiry_date'     => $subscription->expiry_date,
			'first_payment'   => $subscription->first_payment,
			'next_payment'    => $subscription->next_payment,
			'final_payment'   => $subscription->final_payment,
			'renewal_notice'  => $subscription->renewal_notice,
		);

		$date_properties = array(
			'start_date',
			'expiry_date',
			'first_payment',
			'next_payment',
			'final_payment',
			'renewal_notice',
		);

		foreach ( $date_properties as $property ) {
			if ( property_exists( $subscription, $property ) && $subscription->$property instanceof \DateTimeInterface ) {
				$data[ $property ] = $subscription->$property->format( 'Y-m-d H:i:s' );
			}
		}

		$data = array_merge( $subscription->meta, $data );

		foreach ( $data as $key => $value ) {
			if ( ! empty( $value ) ) {
				$meta_key = $prefix . $key;

				update_post_meta( $id, $meta_key, $value );
			}
		}

		if ( $previous_status !== $subscription->status ) {
			$can_redirect = false;

			do_action( 'pronamic_subscription_status_update_' . $subscription->source . '_' . strtolower( $previous_status ) . '_to_' . strtolower( $subscription->status ), $subscription, $can_redirect );
			do_action( 'pronamic_subscription_status_update_' . $subscription->source, $subscription, $can_redirect );
			do_action( 'pronamic_subscription_status_update', $subscription, $can_redirect );
		}
	}
}
