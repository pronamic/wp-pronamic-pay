<?php

class Pronamic_WP_Pay_Subscription extends Pronamic_Pay_Subscription {
	/**
	 * The subscription post object
	 */
	public $post;

	//////////////////////////////////////////////////

	/**
	 * Construct and initialize payment object
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		parent::__construct();

		$this->id   = $post_id;
		$this->post = get_post( $post_id );

		$this->key             = $this->get_meta( 'key' );
		$this->status          = $this->get_meta( 'status' );
		$this->source          = $this->get_meta( 'source' );
		$this->source_id       = $this->get_meta( 'source_id' );
		$this->frequency       = $this->get_meta( 'frequency' );
		$this->interval        = $this->get_meta( 'interval' );
		$this->interval_period = $this->get_meta( 'interval_period' );
		$this->transaction_id  = $this->get_meta( 'transaction_id' );
		$this->description     = $this->get_meta( 'description' );
		$this->currency        = $this->get_meta( 'currency' );
		$this->amount          = $this->get_meta( 'amount' );
	}

	//////////////////////////////////////////////////

	public function add_note( $note ) {
		$commentdata = array(
			'comment_post_ID'      => $this->id,
			'comment_author'       => 'admin',
			'comment_author_email' => 'admin@admin.com',
			'comment_author_url'   => 'http://',
			'comment_content'      => $note,
			'comment_type'         => 'subscription_note',
			'comment_parent'       => 0,
			'user_id'              => 0,
			'comment_approved'     => 1,
		);

		$comment_id = wp_insert_comment( $commentdata );

		return $comment_id;
	}

	//////////////////////////////////////////////////

	public function get_meta( $key ) {
		$key = '_pronamic_subscription_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	public function set_meta( $key, $value = false ) {
		$key = '_pronamic_subscription_' . $key;

		if ( $value instanceof DateTime ) {
			$value = $value->format( 'Y-m-d H:i:s' );
		}

		if ( empty( $value ) ) {
			return delete_post_meta( $this->id, $key );
		}

		return update_post_meta( $this->id, $key, $value );
	}

	//////////////////////////////////////////////////

	/**
	 * Get source description.
	 *
	 * @return string
	 */
	public function get_source_description() {
		$description = $this->source;

		$payment = $this->get_first_payment();

		if ( $payment ) {
			$description = apply_filters( 'pronamic_payment_source_description', $description,  $payment );
			$description = apply_filters( 'pronamic_payment_source_description_' . $this->source, $description, $payment );
		}

		return $description;
	}

	/**
	 * Get source link for this subscription.
	 *
	 * @return string
	 */
	public function get_source_link() {
		$url = null;

		$payment = $this->get_first_payment();

		if ( $payment ) {
			$url = apply_filters( 'pronamic_payment_source_url', $url, $payment );
			$url = apply_filters( 'pronamic_payment_source_url_' . $this->source, $url, $payment );
		}

		return $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get cancel URL for this subscription.
	 *
	 * @return string
	 */
	public function get_cancel_url() {
		$cancel_url = add_query_arg(
			array(
				'subscription' => $this->get_id(),
				'key'          => $this->get_key(),
				'action'       => 'cancel',
			), home_url()
		);

		return $cancel_url;
	}

	/**
	 * Get renewal URL for this subscription.
	 *
	 * @return string
	 */
	public function get_renewal_url() {
		$renewal_url = add_query_arg(
			array(
				'subscription' => $this->get_id(),
				'key'          => $this->get_key(),
				'action'       => 'renew',
			), home_url()
		);

		return $renewal_url;
	}

	//////////////////////////////////////////////////

	public function get_payments() {
		return get_pronamic_payments_by_meta( '_pronamic_payment_subscription_id', $this->id );
	}

	public function has_valid_payment() {
		$payments = $this->get_payments();

		foreach ( $payments as $payment ) {
			if ( Pronamic_WP_Pay_Statuses::SUCCESS === $payment->get_status() ) {
				return $payment;
			}
		}

		return false;
	}

	public function get_first_payment() {
		$payments = $this->get_payments();

		if ( count( $payments ) > 0 ) {
			return $payments[0];
		}

		return null;
	}

	//////////////////////////////////////////////////

	public function get_start_date() {
		return new DateTime( $this->post->post_date_gmt );
	}

	public function get_expiration_date() {
		if ( '' === $this->get_frequency() ) {
			return;
		}

		$expiration = $this->get_start_date();

		return $expiration->modify( sprintf(
			'+%d %s',
			( $this->get_frequency() * $this->get_interval() ),
			Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
		) );
	}

	public function get_first_payment_date() {
		$first_payment = $this->get_first_payment();

		if ( $first_payment ) {
			return new DateTime( $first_payment->post->post_date_gmt );
		}

		return $this->get_start_date();
	}

	public function get_next_payment_datetime( $cycle = 0 ) {
		$next_payment = $this->get_meta( 'next_payment' );

		if ( '' === $next_payment ) {
			return;
		}

		$next = new DateTime( $next_payment );

		if ( 0 !== $cycle ) {
			$next->modify( sprintf(
				'+%d %s',
				( $cycle * $this->get_interval() ),
				Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
			) );
		}

		return $next;
	}

	public function get_final_payment_datetime() {
		$expiration_date = $this->get_expiration_date();

		if ( ! $expiration_date ) {
			return;
		}

		return $expiration_date->modify( sprintf(
			'-%d %s',
			$this->get_interval(),
			Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
		) );
	}
}
