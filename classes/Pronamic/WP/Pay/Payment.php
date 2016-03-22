<?php

class Pronamic_WP_Pay_Payment extends Pronamic_Pay_Payment {
	/**
	 * The payment post object
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

		// Load
		$this->config_id      = get_post_meta( $post_id, '_pronamic_payment_config_id', true );
		$this->key            = get_post_meta( $post_id, '_pronamic_payment_key', true );

		$this->amount         = get_post_meta( $post_id, '_pronamic_payment_amount', true );
		$this->currency       = get_post_meta( $post_id, '_pronamic_payment_currency', true );

		$this->transaction_id = get_post_meta( $post_id, '_pronamic_payment_transaction_id', true );
		$this->action_url     = get_post_meta( $post_id, '_pronamic_payment_action_url', true );

		$this->source         = get_post_meta( $post_id, '_pronamic_payment_source', true );
		$this->source_id      = get_post_meta( $post_id, '_pronamic_payment_source_id', true );

		$this->email          = get_post_meta( $post_id, '_pronamic_payment_email', true );

		$this->status         = get_post_meta( $post_id, '_pronamic_payment_status', true );
	}

	//////////////////////////////////////////////////

	public function add_note( $note ) {
		$commentdata = array(
			'comment_post_ID'      => $this->id,
			'comment_author'       => 'admin',
			'comment_author_email' => 'admin@admin.com',
			'comment_author_url'   => 'http://',
			'comment_content'      => $note,
			'comment_type'         => 'payment_note',
			'comment_parent'       => 0,
			'user_id'              => 0,
			'comment_approved'     => 1,
		);

		$comment_id = wp_insert_comment( $commentdata );

		return $comment_id;
	}

	//////////////////////////////////////////////////

	/**
	 * Source text
	 *
	 * @return string
	 */
	public function get_source_text() {
		$text = $this->get_source() . '<br />' . $this->get_source_id();

		$text = apply_filters( 'pronamic_payment_source_text_' . $this->get_source(), $text, $this );
		$text = apply_filters( 'pronamic_payment_source_text', $text, $this );

		return $text;
	}

	//////////////////////////////////////////////////

	public function get_meta( $key ) {
		$key = '_pronamic_payment_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the return URL for this payment.
	 *
	 * @return string
	 */
	public function get_return_url() {
		$url = add_query_arg(
			array(
				'payment' => $this->id,
				'key'     => $this->key,
			),
			home_url( '/' )
		);

		return $url;
	}

	/**
	 * Get the redirect URL for this payment.
	 *
	 * @return string
	 */
	public function get_redirect_url() {
		$url = home_url( '/' );

		$url = apply_filters( 'pronamic_payment_redirect_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_redirect_url_' . $this->source, $url, $this );

		return $url;
	}
}
