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

	public function get_next_payment_datetime( $cycle = 0 ) {
		$next = new DateTime( $this->get_meta( 'next_payment' ) );

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
		$first = $this->get_first_payment();

		$final = new DateTime( $first->post->post_date_gmt );

		$final->modify( sprintf(
			'+%d %s',
			( $this->get_frequency() * $this->get_interval() ),
			Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
		) );

		return $final;
	}
}
