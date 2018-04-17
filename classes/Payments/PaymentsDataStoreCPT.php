<?php
/**
 * Payments Data Store Custom Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\AbstractDataStoreCPT;
use Pronamic\WordPress\Pay\Core\DateTime;
use Pronamic\WordPress\Pay\Core\DateTimeZone;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Title: Payments data store CPT
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
class PaymentsDataStoreCPT extends AbstractDataStoreCPT {
	/**
	 * Construct payments data store CPT object.
	 */
	public function __construct() {
		$this->meta_key_prefix = '_pronamic_payment_';
	}

	/**
	 * Create payment.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L47-L76
	 *
	 * @param Payment $payment The payment to create in this data store.
	 *
	 * @return bool
	 */
	public function create( Payment $payment ) {
		$title = $payment->title;

		if ( empty( $title ) ) {
			$title = sprintf(
				'Payment – %s',
				date_i18n( _x( '@todo', 'Payment title date format parsed by `date_i18n`.', 'pronamic_ideal' ) )
			);
		}

		$post_status = $this->get_post_status( $payment->status );

		$result = wp_insert_post(
			array(
				'post_type'     => 'pronamic_payment',
				'post_date_gmt' => $this->get_mysql_utc_date( $payment->date ),
				'post_title'    => $title,
				'post_status'   => empty( $post_status ) ? 'payment_pending' : null,
				'post_author'   => $payment->user_id,
			), true
		);

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$payment->set_id( $result );
		$payment->post = get_post( $result );

		$this->update_post_meta( $payment );

		do_action( 'pronamic_pay_new_payment', $payment );

		return true;
	}

	/**
	 * Read payment.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/abstracts/abstract-wc-order.php#L85-L111
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L78-L111
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L81-L136
	 * @see https://developer.wordpress.org/reference/functions/get_post/
	 * @see https://developer.wordpress.org/reference/classes/wp_post/
	 *
	 * @param Payment $payment The payment to read from this data store.
	 */
	public function read( Payment $payment ) {
		$payment->post    = get_post( $payment->get_id() );
		$payment->title   = get_the_title( $payment->get_id() );
		$payment->date    = new DateTime( get_post_field( 'post_date_gmt', $payment->get_id(), 'raw' ), new DateTimeZone( 'UTC' ) );
		$payment->user_id = get_post_field( 'post_author', $payment->get_id(), 'raw' );

		$this->read_post_meta( $payment );
	}

	/**
	 * Update payment.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/abstract-wc-order-data-store-cpt.php#L113-L154
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Payment $payment The payment to update in this data store.
	 */
	public function update( Payment $payment ) {
		$data = array(
			'ID' => $payment->get_id(),
		);

		$post_status = $this->get_post_status( $payment->status );

		if ( ! empty( $post_status ) ) {
			$data['post_status'] = $post_status;
		}

		wp_update_post( $data );

		$this->update_post_meta( $payment );
	}

	/**
	 * Get post status.
	 *
	 * @param string $meta_status The payment to get a WordPress post status for.
	 *
	 * @return string|null
	 */
	public function get_post_status( $meta_status ) {
		switch ( $meta_status ) {
			case Statuses::CANCELLED:
				return 'payment_cancelled';
			case Statuses::EXPIRED:
				return 'payment_expired';
			case Statuses::FAILURE:
				return 'payment_failed';
			case Statuses::SUCCESS:
				return 'payment_completed';
			case Statuses::OPEN:
				return 'payment_pending';
			default:
				return null;
		}
	}

	/**
	 * Get meta status label.
	 *
	 * @param string $meta_status The subscription meta status to get the status label for.
	 * @return string|boolean
	 */
	public function get_meta_status_label( $meta_status ) {
		$post_status = $this->get_post_status( $meta_status );

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
	 * @param Payment $payment The payment to read.
	 */
	private function read_post_meta( $payment ) {
		$id = $payment->get_id();

		$payment->config_id           = $this->get_meta( $id, 'config_id' );
		$payment->key                 = $this->get_meta( $id, 'key' );
		$payment->amount              = (float) $this->get_meta( $id, 'amount' );
		$payment->currency            = $this->get_meta( $id, 'currency' );
		$payment->method              = $this->get_meta( $id, 'method' );
		$payment->issuer              = $this->get_meta( $id, 'issuer' );
		$payment->order_id            = $this->get_meta( $id, 'order_id' );
		$payment->transaction_id      = $this->get_meta( $id, 'transaction_id' );
		$payment->entrance_code       = $this->get_meta( $id, 'entrance_code' );
		$payment->action_url          = $this->get_meta( $id, 'action_url' );
		$payment->source              = $this->get_meta( $id, 'source' );
		$payment->source_id           = $this->get_meta( $id, 'source_id' );
		$payment->description         = $this->get_meta( $id, 'description' );
		$payment->language            = $this->get_meta( $id, 'language' );
		$payment->locale              = $this->get_meta( $id, 'locale' );
		$payment->email               = $this->get_meta( $id, 'email' );
		$payment->status              = $this->get_meta( $id, 'status' );
		$payment->customer_name       = $this->get_meta( $id, 'customer_name' );
		$payment->address             = $this->get_meta( $id, 'address' );
		$payment->zip                 = $this->get_meta( $id, 'zip' );
		$payment->city                = $this->get_meta( $id, 'city' );
		$payment->country             = $this->get_meta( $id, 'country' );
		$payment->telephone_number    = $this->get_meta( $id, 'telephone_number' );
		$payment->analytics_client_id = $this->get_meta( $id, 'analytics_client_id' );
		$payment->subscription_id     = $this->get_meta( $id, 'subscription_id' );
		$payment->recurring_type      = $this->get_meta( $id, 'recurring_type' );
		$payment->recurring           = $this->get_meta( $id, 'recurring' );
		$payment->start_date          = $this->get_meta_date( $id, 'start_date' );
		$payment->end_date            = $this->get_meta_date( $id, 'end_date' );
		$payment->user_agent          = $this->get_meta( $id, 'user_agent' );
		$payment->user_ip             = $this->get_meta( $id, 'user_ip' );
	}

	/**
	 * Update payment post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Payment $payment The payment to update.
	 */
	private function update_post_meta( $payment ) {
		$id = $payment->get_id();

		$this->update_meta( $id, 'config_id', $payment->config_id );
		$this->update_meta( $id, 'key', $payment->key );
		$this->update_meta( $id, 'order_id', $payment->order_id );
		$this->update_meta( $id, 'currency', $payment->currency );
		$this->update_meta( $id, 'amount', $payment->amount );
		$this->update_meta( $id, 'method', $payment->method );
		$this->update_meta( $id, 'issuer', $payment->issuer );
		$this->update_meta( $id, 'expiration_period', null );
		$this->update_meta( $id, 'language', $payment->language );
		$this->update_meta( $id, 'locale', $payment->locale );
		$this->update_meta( $id, 'entrance_code', $payment->entrance_code );
		$this->update_meta( $id, 'description', $payment->description );
		$this->update_meta( $id, 'first_name', $payment->first_name );
		$this->update_meta( $id, 'last_name', $payment->last_name );
		$this->update_meta( $id, 'consumer_name', $payment->consumer_name );
		$this->update_meta( $id, 'consumer_account_number', $payment->consumer_account_number );
		$this->update_meta( $id, 'consumer_iban', $payment->consumer_iban );
		$this->update_meta( $id, 'consumer_bic', $payment->consumer_bic );
		$this->update_meta( $id, 'consumer_city', $payment->consumer_city );
		$this->update_meta( $id, 'source', $payment->source );
		$this->update_meta( $id, 'source_id', $payment->source_id );
		$this->update_meta( $id, 'email', $payment->email );
		$this->update_meta( $id, 'customer_name', $payment->customer_name );
		$this->update_meta( $id, 'address', $payment->address );
		$this->update_meta( $id, 'zip', $payment->zip );
		$this->update_meta( $id, 'city', $payment->city );
		$this->update_meta( $id, 'country', $payment->country );
		$this->update_meta( $id, 'telephone_number', $payment->telephone_number );
		$this->update_meta( $id, 'analytics_client_id', $payment->analytics_client_id );
		$this->update_meta( $id, 'subscription_id', $payment->subscription_id );
		$this->update_meta( $id, 'recurring_type', $payment->recurring_type );
		$this->update_meta( $id, 'recurring', $payment->recurring );
		$this->update_meta( $id, 'transaction_id', $payment->get_transaction_id() );
		$this->update_meta( $id, 'action_url', $payment->get_action_url() );
		$this->update_meta( $id, 'start_date', $payment->start_date );
		$this->update_meta( $id, 'end_date', $payment->end_date );
		$this->update_meta( $id, 'user_agent', $payment->user_agent );
		$this->update_meta( $id, 'user_ip', $payment->user_ip );

		$this->update_meta_status( $payment );
	}

	/**
	 * Update meta status.
	 *
	 * @param Payment $payment The payment to update the status for.
	 */
	public function update_meta_status( $payment ) {
		$id = $payment->get_id();

		$previous_status = $this->get_meta( $id, 'status' );

		$this->update_meta( $id, 'status', $payment->status );

		if ( $previous_status !== $payment->status ) {
			$old = $previous_status;
			$old = strtolower( $old );
			$old = empty( $old ) ? 'unknown' : $old;

			$new = $payment->status;
			$new = strtolower( $new );
			$new = empty( $new ) ? 'unknown' : $new;

			$can_redirect = false;

			do_action( 'pronamic_payment_status_update_' . $payment->source . '_' . $old . '_to_' . $new, $payment, $can_redirect, $previous_status, $payment->status );
			do_action( 'pronamic_payment_status_update_' . $payment->source, $payment, $can_redirect, $previous_status, $payment->status );
			do_action( 'pronamic_payment_status_update', $payment, $can_redirect, $previous_status, $payment->status );
		}
	}
}
