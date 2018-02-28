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
class PaymentsDataStoreCPT {
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

		$result = wp_insert_post(
			array(
				'post_type'     => 'pronamic_payment',
				'post_date_gmt' => $payment->date->format( 'Y-m-d H:i:s' ),
				'post_title'    => $title,
				'post_status'   => $this->get_post_status( $payment ),
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
		$payment->date    = new \DateTime( get_post_field( 'post_date_gmt', $payment->get_id(), 'raw' ) );
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

		$post_status = $this->get_post_status( $payment, null );

		if ( null !== $post_status ) {
			$data['post_status'] = $post_status;
		}

		wp_update_post( $data );

		$this->update_post_meta( $payment );
	}

	/**
	 * Get post status.
	 *
	 * @param Payment $payment The payment to get a WordPress post status for.
	 * @param string  $default The default WordPress post status to return.
	 *
	 * @return string
	 */
	private function get_post_status( $payment, $default = 'payment_pending' ) {
		switch ( $payment->status ) {
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
				return $default;
		}
	}

	/**
	 * Read post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/abstracts/abstract-wc-data.php#L462-L507
	 * @param Payment $payment The payment to read.
	 */
	private function read_post_meta( $payment ) {
		$prefix = '_pronamic_payment_';

		$id = $payment->get_id();

		$payment->config_id = get_post_meta( $id, $prefix . 'config_id', true );
		$payment->key       = get_post_meta( $id, $prefix . 'key', true );

		$payment->amount   = (float) get_post_meta( $id, $prefix . 'amount', true );
		$payment->currency = get_post_meta( $id, $prefix . 'currency', true );
		$payment->method   = get_post_meta( $id, $prefix . 'method', true );
		$payment->issuer   = get_post_meta( $id, $prefix . 'issuer', true );

		$payment->order_id       = get_post_meta( $id, $prefix . 'order_id', true );
		$payment->transaction_id = get_post_meta( $id, $prefix . 'transaction_id', true );
		$payment->entrance_code  = get_post_meta( $id, $prefix . 'entrance_code', true );
		$payment->action_url     = get_post_meta( $id, $prefix . 'action_url', true );

		$payment->source      = get_post_meta( $id, $prefix . 'source', true );
		$payment->source_id   = get_post_meta( $id, $prefix . 'source_id', true );
		$payment->description = get_post_meta( $id, $prefix . 'description', true );

		$payment->language = get_post_meta( $id, $prefix . 'language', true );
		$payment->locale   = get_post_meta( $id, $prefix . 'locale', true );
		$payment->email    = get_post_meta( $id, $prefix . 'email', true );

		$payment->status = get_post_meta( $id, $prefix . 'status', true );

		$payment->customer_name       = get_post_meta( $id, $prefix . 'customer_name', true );
		$payment->address             = get_post_meta( $id, $prefix . 'address', true );
		$payment->zip                 = get_post_meta( $id, $prefix . 'zip', true );
		$payment->city                = get_post_meta( $id, $prefix . 'city', true );
		$payment->country             = get_post_meta( $id, $prefix . 'country', true );
		$payment->telephone_number    = get_post_meta( $id, $prefix . 'telephone_number', true );
		$payment->analytics_client_id = get_post_meta( $id, $prefix . 'analytics_client_id', true );

		$payment->subscription_id = get_post_meta( $id, $prefix . 'subscription_id', true );
		$payment->recurring_type  = get_post_meta( $id, $prefix . 'recurring_type', true );
		$payment->recurring       = get_post_meta( $id, $prefix . 'recurring', true );

		// Start Date.
		$start_date_string = get_post_meta( $id, $prefix . 'start_date', true );

		if ( ! empty( $start_date_string ) ) {
			$payment->start_date = date_create( $start_date_string );
		}

		// End Date.
		$end_date_string = get_post_meta( $id, $prefix . 'end_date', true );

		if ( ! empty( $end_date_string ) ) {
			$payment->end_date = date_create( $end_date_string );
		}
	}

	/**
	 * Update payment post meta.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/data-stores/class-wc-order-data-store-cpt.php#L154-L257
	 * @param Payment $payment The payment to update.
	 */
	private function update_post_meta( $payment ) {
		$prefix = '_pronamic_payment_';

		$previous_status = get_post_meta( $payment->get_id(), '_pronamic_payment_status', true );
		$previous_status = strtolower( $previous_status );
		$previous_status = empty( $previous_status ) ? 'unknown' : $previous_status;

		$data = array(
			'config_id'               => $payment->config_id,
			'key'                     => $payment->key,
			'order_id'                => $payment->order_id,
			'currency'                => $payment->currency,
			'amount'                  => $payment->amount,
			'method'                  => $payment->method,
			'issuer'                  => $payment->issuer,
			'expiration_period'       => null,
			'language'                => $payment->language,
			'locale'                  => $payment->locale,
			'entrance_code'           => $payment->entrance_code,
			'description'             => $payment->description,
			'first_name'              => $payment->first_name,
			'last_name'               => $payment->last_name,
			'consumer_name'           => $payment->consumer_name,
			'consumer_account_number' => $payment->consumer_account_number,
			'consumer_iban'           => $payment->consumer_iban,
			'consumer_bic'            => $payment->consumer_bic,
			'consumer_city'           => $payment->consumer_city,
			'status'                  => $payment->status,
			'source'                  => $payment->source,
			'source_id'               => $payment->source_id,
			'email'                   => $payment->email,
			'customer_name'           => $payment->customer_name,
			'address'                 => $payment->address,
			'zip'                     => $payment->zip,
			'city'                    => $payment->city,
			'country'                 => $payment->country,
			'telephone_number'        => $payment->telephone_number,
			'analytics_client_id'     => $payment->analytics_client_id,
			'subscription_id'         => $payment->subscription_id,
			'recurring_type'          => $payment->recurring_type,
			'recurring'               => $payment->recurring,
			'transaction_id'          => $payment->get_transaction_id(),
			'action_url'              => $payment->get_action_url(),
		);

		if ( isset( $payment->start_date ) ) {
			$data['start_date'] = $payment->start_date->format( 'Y-m-d H:i:s' );
		}

		if ( isset( $payment->end_date ) ) {
			$data['end_date'] = $payment->end_date->format( 'Y-m-d H:i:s' );
		}

		$data = array_merge( $payment->meta, $data );

		foreach ( $data as $key => $value ) {
			if ( ! empty( $value ) ) {
				$meta_key = $prefix . $key;

				update_post_meta( $payment->get_id(), $meta_key, $value );
			}
		}

		if ( $previous_status !== $payment->status ) {
			$can_redirect = false;

			do_action( 'pronamic_payment_status_update_' . $payment->source . '_' . $previous_status . '_to_' . $payment->status, $payment, $can_redirect );
			do_action( 'pronamic_payment_status_update_' . $payment->source, $payment, $can_redirect );
			do_action( 'pronamic_payment_status_update', $payment, $can_redirect );
		}
	}
}
