<?php
/**
 * Payment
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use Pronamic\WordPress\Pay\Currency;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Payment
 *
 * @author Remco Tolsma
 * @version 4.4.3
 * @since 1.0.0
 */
class Payment {
	/**
	 * The payment post object.
	 *
	 * @var \WP_Post
	 */
	public $post;

	/**
	 * The subscription.
	 *
	 * @var Subscription
	 */
	public $subscription;

	/**
	 * The unique ID of this payment.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The title of this payment.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * The configuration ID.
	 *
	 * @var string
	 */
	public $config_id;

	/**
	 * The key of this payment, used in URL's for security.
	 *
	 * @var string
	 */
	public $key;

	/**
	 * Identifier for the source which started this payment.
	 * For example: 'woocommerce', 'gravityforms', 'easydigitaldownloads', etc.
	 *
	 * @var string
	 */
	public $source;

	/**
	 * Unique ID at the source which started this payment, for example:
	 * - WooCommerce order ID.
	 * - Easy Digital Downloads payment ID.
	 * - Gravity Forms entry ID.
	 *
	 * @var string
	 */
	public $source_id;

	/**
	 * The purchase ID.
	 *
	 * @todo Is this required/used?
	 * @var string
	 */
	public $purchase_id;

	/**
	 * The transaction ID of this payment.
	 *
	 * @var string
	 */
	public $transaction_id;

	/**
	 * The order ID of this payment.
	 *
	 * @todo Is this required/used?
	 * @var string
	 */
	public $order_id;

	/**
	 * The amount of this payment, for example 18.95.
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * The currency of this subscription, for example 'EUR' or 'USD'.
	 *
	 * @var string
	 */
	public $currency;

	/**
	 * The expiration period of this payment.
	 *
	 * @todo Is this required/used?
	 * @var string
	 */
	public $expiration_period;

	/**
	 * The language of the user who started this payment.
	 *
	 * @var string
	 */
	public $language;

	/**
	 * The locale of the user who started this payment.
	 *
	 * @var string
	 */
	public $locale;

	/**
	 * The entrance code of this payment.
	 *
	 * @todo Is this required/used?
	 * @var string
	 */
	public $entrance_code;

	/**
	 * The description of this payment.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * The name of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_name;

	/**
	 * The account number of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_account_number;

	/**
	 * The IBAN of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_iban;

	/**
	 * The BIC of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_bic;

	/**
	 * The city of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_city;

	/**
	 * The customer name of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $customer_name;

	/**
	 * The address of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $address;

	/**
	 * The city of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $city;

	/**
	 * The ZIP of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $zip;

	/**
	 * The country of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $country;

	/**
	 * The telephone number of the consumer of this payment.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $telephone_number;

	/**
	 * The Google Analytics client ID of the user who started this payment.
	 *
	 * @var string
	 */
	public $analytics_client_id;

	/**
	 * The status of this payment.
	 *
	 * @todo   Check constant?
	 * @var string
	 */
	public $status;

	/**
	 * Status requests?
	 *
	 * @todo What is this?
	 * @var ?
	 */
	public $status_requests;

	/**
	 * The email of the user who started this payment.
	 *
	 * @var string
	 */
	public $email;

	/**
	 * The action URL for this payment.
	 *
	 * @var string
	 */
	public $action_url;

	/**
	 * The payment method chosen by to user who started this payment.
	 *
	 * @var string
	 */
	public $method;

	/**
	 * The issuer chosen by to user who started this payment.
	 *
	 * @var string
	 */
	public $issuer;

	/**
	 * Subscription ID.
	 *
	 * @todo Is this required?
	 * @var string
	 */
	public $subscription_id;

	/**
	 * Flag to indicate a recurring payment
	 *
	 * @todo Is this required?
	 * @var boolean
	 */
	public $recurring;

	/**
	 * The first name of the user who started this payment.
	 *
	 * @var string
	 */
	public $first_name;

	/**
	 * The last name of the user who started this payment.
	 *
	 * @var string
	 */
	public $last_name;

	/**
	 * The recurring type.
	 *
	 * @todo Improve documentation, is this used?
	 * @var string
	 */
	public $recurring_type;

	/**
	 * Meta.
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * Start date if the payment is related to a specific period.
	 *
	 * @var DateTime
	 */
	public $start_date;

	/**
	 * End date if the payment is related to a specific period.
	 *
	 * @var DateTime
	 */
	public $end_date;

	/**
	 * Construct and initialize payment object.
	 *
	 * @param int $post_id A payment post ID or null.
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

	/**
	 * Get the ID of this payment.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the ID of this payment.
	 *
	 * @param string $id Unique ID.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Add a note to this payment.
	 *
	 * @param string $note The note to add.
	 */
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

	/**
	 * Get the source identifier of this payment.
	 *
	 * @return string
	 */
	public function get_source() {
		return $this->source;
	}

	/**
	 * Get the source ID of this payment.
	 *
	 * @return string
	 */
	public function get_source_id() {
		return $this->source_id;
	}

	/**
	 * Get the source text of this payment.
	 *
	 * @return string
	 */
	public function get_source_text() {
		$text = $this->get_source() . '<br />' . $this->get_source_id();

		$text = apply_filters( 'pronamic_payment_source_text_' . $this->get_source(), $text, $this );
		$text = apply_filters( 'pronamic_payment_source_text', $text, $this );

		return $text;
	}

	/**
	 * Get the order ID of this payment.
	 *
	 * @return string
	 */
	public function get_order_id() {
		return $this->order_id;
	}

	/**
	 * Get the payment amount.
	 *
	 * @return float
	 */
	public function get_amount() {
		return $this->amount;
	}

	/**
	 * Get the payment currency.
	 *
	 * @return string
	 */
	public function get_currency() {
		return $this->currency;
	}

	/**
	 * Get currency numeric code
	 *
	 * @return string|null
	 */
	public function get_currency_numeric_code() {
		return Currency::transform_code_to_number( $this->get_currency() );
	}

	/**
	 * Get the payment method.
	 *
	 * @todo Constant?
	 * @return string
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Get the payment issuer.
	 *
	 * @return string
	 */
	public function get_issuer() {
		return $this->issuer;
	}

	/**
	 * Get the payment language.
	 *
	 * @return string
	 */
	public function get_language() {
		return $this->language;
	}

	/**
	 * Get the payment locale.
	 *
	 * @return string
	 */
	public function get_locale() {
		return $this->locale;
	}

	/**
	 * Get the payment description.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Set the transaction ID.
	 *
	 * @param string $transaction_id Transaction ID.
	 */
	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
	}

	/**
	 * Get the payment transaction ID.
	 *
	 * @return string
	 */
	public function get_transaction_id() {
		return $this->transaction_id;
	}

	/**
	 * Get the payment status.
	 *
	 * @todo Constant?
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Set the payment status.
	 *
	 * @param string $status Status.
	 */
	public function set_status( $status ) {
		$this->status = $status;
	}

	/**
	 * Get the meta value of this specified meta key.
	 *
	 * @param string $key Meta key.
	 * @return mixed
	 */
	public function get_meta( $key ) {
		$key = '_pronamic_payment_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	/**
	 * Set meta value at the specified key.
	 *
	 * @param string $key   Meta key.
	 * @param string $value Meta value.
	 */
	public function set_meta( $key, $value ) {
		$this->meta[ $key ] = $value;
	}

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
	 *
	 * @return string
	 */
	public function get_action_url() {
		if ( '' === $this->get_amount() || 0.0 === $this->get_amount() ) {
			$this->set_status( Statuses::SUCCESS );

			return $this->get_return_redirect_url();
		}

		return $this->action_url;
	}

	/**
	 * Set the action URL.
	 *
	 * @param string $action_url Action URL.
	 */
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

	/**
	 * Format string
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/v2.2.3/includes/abstracts/abstract-wc-email.php#L187-L195
	 *
	 * @param string $string The string to format.
	 * @return string
	 */
	public function format_string( $string ) {
		// Replacements definition.
		$replacements = array(
			'{order_id}'   => $this->get_order_id(),
			'{payment_id}' => $this->get_id(),
		);

		// Find and replace.
		$string = str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$string,
			$count
		);

		// Make sure there is an dynamic part in the order ID.
		// @see https://secure.ogone.com/ncol/param_cookbook.asp.
		if ( 0 === $count ) {
			$string .= $this->get_id();
		}

		return $string;
	}

	/**
	 * Set consumer name.
	 *
	 * @param string $name Name.
	 */
	public function set_consumer_name( $name ) {
		$this->consumer_name = $name;
	}

	/**
	 * Set consumer account number.
	 *
	 * @param string $account_number Account number.
	 */
	public function set_consumer_account_number( $account_number ) {
		$this->consumer_account_number = $account_number;
	}

	/**
	 * Set consumer IBAN.
	 *
	 * @param string $iban IBAN.
	 */
	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	/**
	 * Set consumer BIC.
	 *
	 * @param string $bic BIC.
	 */
	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}

	/**
	 * Set consumer city.
	 *
	 * @param string $city City.
	 */
	public function set_consumer_city( $city ) {
		$this->consumer_city = $city;
	}

	/**
	 * Get payment email.
	 *
	 * @return string
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * Get first name.
	 *
	 * @return string
	 */
	public function get_first_name() {
		return $this->first_name;
	}

	/**
	 * Get last name.
	 *
	 * @return string
	 */
	public function get_last_name() {
		return $this->last_name;
	}

	/**
	 * Get customer name.
	 *
	 * @return string
	 */
	public function get_customer_name() {
		return $this->customer_name;
	}

	/**
	 * Get address.
	 *
	 * @return string
	 */
	public function get_address() {
		return $this->address;
	}

	/**
	 * Get city.
	 *
	 * @return string
	 */
	public function get_city() {
		return $this->city;
	}

	/**
	 * Get ZIP.
	 *
	 * @return string
	 */
	public function get_zip() {
		return $this->zip;
	}

	/**
	 * Get country.
	 *
	 * @return string
	 */
	public function get_country() {
		return $this->country;
	}

	/**
	 * Get telephone number.
	 *
	 * @return string
	 */
	public function get_telephone_number() {
		return $this->telephone_number;
	}

	/**
	 * Get Google Analytics client ID.
	 *
	 * @return string
	 */
	public function get_analytics_client_id() {
		return $this->analytics_client_id;
	}

	/**
	 * Get entrance code.
	 *
	 * @return string
	 */
	public function get_entrance_code() {
		return $this->entrance_code;
	}

	/**
	 * Set the credit card to use for this payment.
	 *
	 * @param CreditCard $credit_card Credit Card.
	 */
	public function set_credit_card( $credit_card ) {
		$this->credit_card = $credit_card;
	}

	/**
	 * Get the credit card to use for this payment.
	 *
	 * @return CreditCard|null
	 */
	public function get_credit_card() {
		return $this->credit_card;
	}

	/**
	 * Get payment subscription ID.
	 *
	 * @return string
	 */
	public function get_subscription_id() {
		return $this->subscription_id;
	}

	/**
	 * Get reucrring.
	 *
	 * @return TODO
	 */
	public function get_recurring() {
		return $this->recurring;
	}
}
