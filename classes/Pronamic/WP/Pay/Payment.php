<?php

/**
 * Title: WordPress payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.4.3
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Payment extends Pronamic_Pay_Payment {
	/**
	 * The payment post object
	 */
	public $post;

	/**
	 * The subscription
	 *
	 * @var Pronamic_Pay_WP_Subscription
	 */
	public $subscription;

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
		$this->method         = get_post_meta( $post_id, '_pronamic_payment_method', true );
		$this->issuer         = get_post_meta( $post_id, '_pronamic_payment_issuer', true );

		$this->order_id       = get_post_meta( $post_id, '_pronamic_payment_order_id', true );
		$this->transaction_id = get_post_meta( $post_id, '_pronamic_payment_transaction_id', true );
		$this->entrance_code  = get_post_meta( $post_id, '_pronamic_payment_entrance_code', true );
		$this->action_url     = get_post_meta( $post_id, '_pronamic_payment_action_url', true );

		$this->source         = get_post_meta( $post_id, '_pronamic_payment_source', true );
		$this->source_id      = get_post_meta( $post_id, '_pronamic_payment_source_id', true );
		$this->description    = get_post_meta( $post_id, '_pronamic_payment_description', true );

		$this->language       = get_post_meta( $post_id, '_pronamic_payment_language', true );
		$this->locale         = get_post_meta( $post_id, '_pronamic_payment_locale', true );
		$this->email          = get_post_meta( $post_id, '_pronamic_payment_email', true );

		$this->status         = get_post_meta( $post_id, '_pronamic_payment_status', true );

		$this->customer_name    = $this->get_meta( 'customer_name' );
		$this->address          = $this->get_meta( 'address' );
		$this->zip              = $this->get_meta( 'zip' );
		$this->city             = $this->get_meta( 'city' );
		$this->country          = $this->get_meta( 'country' );
		$this->telephone_number = $this->get_meta( 'telephone_number' );

		$this->subscription_id  = $this->get_meta( 'subscription_id' );
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
	 * Get the pay redirect URL.
	 *
	 * @return string
	 */
	public function get_pay_redirect_url() {
		return add_query_arg( 'payment_redirect', $this->id, home_url( '/' ) );
	}

	/**
	 * Get the return URL for this payment. This URL is passed to the payment providers / gateways
	 * so they know where they should return users to.
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
	 * Get the return redirect URL for this payment. This URL is used after a user is returned
	 * from a payment provider / gateway to WordPress. It allows WordPress payment extensions
	 * to redirect users to the correct URL.
	 *
	 * @return string
	 */
	public function get_return_redirect_url() {
		$url = home_url( '/' );

		$url = apply_filters( 'pronamic_payment_redirect_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_redirect_url_' . $this->source, $url, $this );

		return $url;
	}

	/**
	 * Get the redirect URL for this payment.
	 *
	 * @deprecated 4.1.2 Use get_return_redirect_url()
	 * @return string
	 */
	public function get_redirect_url() {
		_deprecated_function( __FUNCTION__, '4.1.2', 'get_return_redirect_url()' );

		return $this->get_return_redirect_url();
	}

	//////////////////////////////////////////////////

	/**
	 * Get subscription.
	 *
	 * @return Pronamic_WP_Pay_Subscription
	 */
	public function get_subscription() {
		if ( is_object( $this->subscription ) ) {
			return $this->subscription;
		}

		if ( empty( $this->subscription_id ) ) {
			return false;
		}

		$this->subscription = new Pronamic_WP_Pay_Subscription( $this->subscription_id );

		return $this->subscription;
	}

	//////////////////////////////////////////////////

	/**
	 * Format string
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/v2.2.3/includes/abstracts/abstract-wc-email.php#L187-L195
	 * @param string $string
	 * @return string
	 */
	public function format_string( $string ) {
		// Replacements definition
		$replacements = array(
			'{order_id}'   => $this->get_order_id(),
			'{payment_id}' => $this->get_id(),
		);

		// Find and replace
		$string = str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$string,
			$count
		);

		// Make sure there is an dynamic part in the order ID
		// @see https://secure.ogone.com/ncol/param_cookbook.asp
		if ( 0 === $count ) {
			$string .= $this->get_id();
		}

		return $string;
	}
}
