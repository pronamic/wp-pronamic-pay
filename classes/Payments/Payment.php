<?php

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use Pronamic\WordPress\Pay\Currency;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Title: WordPress payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.4.3
 * @since 1.0.0
 */
class Payment {
	/**
	 * The payment post object
	 */
	public $post;

	/**
	 * The subscription
	 *
	 * @var Subscription
	 */
	public $subscription;

	//////////////////////////////////////////////////

	protected $id;

	public $config_id;

	public $key;

	//////////////////////////////////////////////////
	// Source
	//////////////////////////////////////////////////

	public $source;

	public $source_id;

	//////////////////////////////////////////////////

	public $purchase_id;

	public $transaction_id;

	public $order_id;

	public $amount;

	public $currency;

	public $expiration_period;

	public $language;

	public $locale;

	public $entrance_code;

	public $description;

	public $consumer_name;

	public $consumer_account_number;

	public $consumer_iban;

	public $consumer_bic;

	public $consumer_city;

	public $customer_name;

	public $address;

	public $city;

	public $zip;

	public $country;

	public $telephone_number;

	public $analytics_client_id;

	public $status;

	public $status_requests;

	public $email;

	public $action_url;

	public $method;

	public $issuer;

	public $subscription_id;

	public $recurring;

	public $first_name;

	public $last_name;

	//////////////////////////////////////////////////

	/**
	 * Meta
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * Construct and initialize payment object
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id = null ) {
		$this->id   = $post_id;
		$this->date = new \DateTime();
		$this->meta = array();

		if ( null !== $post_id ) {
			global $pronamic_ideal;

			$pronamic_ideal->payments_data_store->read( $this );
		}
	}

	//////////////////////////////////////////////////

	public function get_id() {
		return $this->id;
	}

	public function set_id( $id ) {
		$this->id = $id;
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

	public function get_source() {
		return $this->source;
	}

	public function get_source_id() {
		return $this->source_id;
	}

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

	public function get_order_id() {
		return $this->order_id;
	}

	//////////////////////////////////////////////////

	public function get_amount() {
		return $this->amount;
	}

	//////////////////////////////////////////////////

	public function get_currency() {
		return $this->currency;
	}

	/**
	 * Get currency numeric code
	 *
	 * @return Ambigous <string, NULL>
	 */
	public function get_currency_numeric_code() {
		return Currency::transform_code_to_number( $this->get_currency() );
	}

	//////////////////////////////////////////////////

	public function get_method() {
		return $this->method;
	}

	//////////////////////////////////////////////////

	public function get_issuer() {
		return $this->issuer;
	}

	//////////////////////////////////////////////////

	public function get_language() {
		return $this->language;
	}

	//////////////////////////////////////////////////

	public function get_locale() {
		return $this->locale;
	}

	//////////////////////////////////////////////////

	public function get_description() {
		return $this->description;
	}

	//////////////////////////////////////////////////

	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
	}

	public function get_transaction_id() {
		return $this->transaction_id;
	}

	//////////////////////////////////////////////////

	public function get_status() {
		return $this->status;
	}

	public function set_status( $status ) {
		$this->status = $status;
	}

	//////////////////////////////////////////////////

	public function get_meta( $key ) {
		$key = '_pronamic_payment_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	public function set_meta( $key, $value ) {
		$this->meta[ $key ] = $value;
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
	 * Get action URL.
	 */
	public function get_action_url() {
		if ( '' === $this->get_amount() || 0.0 === $this->get_amount() ) {
			$this->set_status( Statuses::SUCCESS );

			return $this->get_return_redirect_url();
		}

		return $this->action_url;
	}

	public function set_action_url( $action_url ) {
		$this->action_url = $action_url;
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

	/**
	 * Get source description.
	 *
	 * @return string
	 */
	public function get_source_description() {
		$description = $this->source;

		$description = apply_filters( 'pronamic_payment_source_description', $description, $this );
		$description = apply_filters( 'pronamic_payment_source_description_' . $this->source, $description, $this );

		return $description;
	}

	/**
	 * Get the source link for this payment.
	 *
	 * @return string
	 */
	public function get_source_link() {
		$url = null;

		$url = apply_filters( 'pronamic_payment_source_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_source_url_' . $this->source, $url, $this );

		return $url;
	}

	/**
	 * Get provider link for this payment.
	 *
	 * @return string
	 */
	public function get_provider_link() {
		$url = null;

		$config_id  = get_post_meta( $this->id, '_pronamic_payment_config_id', true );
		$gateway_id = get_post_meta( $config_id, '_pronamic_gateway_id', true );

		$url = apply_filters( 'pronamic_payment_provider_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_provider_url_' . $gateway_id, $url, $this );

		return $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get subscription.
	 *
	 * @return Subscription
	 */
	public function get_subscription() {
		if ( is_object( $this->subscription ) ) {
			return $this->subscription;
		}

		if ( empty( $this->subscription_id ) ) {
			return false;
		}

		$this->subscription = new Subscription( $this->subscription_id );

		return $this->subscription;
	}

	//////////////////////////////////////////////////

	/**
	 * Format string
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/v2.2.3/includes/abstracts/abstract-wc-email.php#L187-L195
	 *
	 * @param string $string
	 *
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

	//////////////////////////////////////////////////

	public function set_consumer_name( $consumer_name ) {
		$this->consumer_name = $consumer_name;
	}

	//////////////////////////////////////////////////

	public function set_consumer_account_number( $account_number ) {
		$this->consumer_account_number = $account_number;
	}

	//////////////////////////////////////////////////

	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	//////////////////////////////////////////////////

	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}

	//////////////////////////////////////////////////

	public function set_consumer_city( $city ) {
		$this->consumer_city = $city;
	}

	//////////////////////////////////////////////////

	public function get_email() {
		return $this->email;
	}

	//////////////////////////////////////////////////

	public function get_first_name() {
		return $this->first_name;
	}

	public function get_last_name() {
		return $this->last_name;
	}

	public function get_customer_name() {
		return $this->customer_name;
	}

	public function get_address() {
		return $this->address;
	}

	public function get_city() {
		return $this->city;
	}

	public function get_zip() {
		return $this->zip;
	}

	public function get_country() {
		return $this->country;
	}

	public function get_telephone_number() {
		return $this->telephone_number;
	}

	public function get_analytics_client_id() {
		return $this->analytics_client_id;
	}

	public function get_entrance_code() {
		return $this->entrance_code;
	}

	//////////////////////////////////////////////////

	public function set_credit_card( $credit_card ) {
		$this->credit_card = $credit_card;
	}

	public function get_credit_card() {
		return $this->credit_card;
	}

	//////////////////////////////////////////////////

	public function get_subscription_id() {
		return $this->subscription_id;
	}

	public function get_recurring() {
		return $this->recurring;
	}
}
