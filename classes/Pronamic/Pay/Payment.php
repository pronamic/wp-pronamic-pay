<?php

class Pronamic_Pay_Payment {

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

	//////////////////////////////////////////////////

	/**
	 * Meta
	 *
	 * @var array
	 */
	public $meta;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an payment object
	 */
	public function __construct() {
		$this->meta = array();
	}

	//////////////////////////////////////////////////

	public function get_id() {
		return $this->id;
	}

	public function set_id( $id ) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	public function get_source() {
		return $this->source;
	}

	public function get_source_id() {
		return $this->source_id;
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
		return Pronamic_WP_Currency::transform_code_to_number( $this->get_currency() );
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

	public function get_action_url() {
		return $this->action_url;
	}

	public function set_action_url( $action_url ) {
		$this->action_url = $action_url;
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

	public function add_note( $note ) {

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

	//////////////////////////////////////////////////

	public function get_meta( $key ) {
		$value = null;

		if ( isset( $this->meta[ $key ] ) ) {
			$value = $this->meta[ $key ];
		}

		return $value;
	}

	public function set_meta( $key, $value ) {
		$this->meta[ $key ] = $value;
	}
}
