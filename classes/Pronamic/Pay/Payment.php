<?php

class Pronamic_Pay_Payment {

	protected $id;

	//////////////////////////////////////////////////
	// Source
	//////////////////////////////////////////////////

	public $source;

	public $source_id;
	
	//////////////////////////////////////////////////
	
	public $purchase_id;
	
	public $transaction_id;
	
	public $amount;
	
	public $currency;
	
	public $expiration_period;
	
	public $language;
	
	public $entrance_code;
	
	public $description;
	
	public $consumer_name;
	
	public $consumer_account_number;
	
	public $consumer_iban;
	
	public $consumer_bic;
	
	public $consumer_city;
	
	public $status;
	
	public $status_requests;
	
	public $email;
	
	public $action_url;

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

	public function get_amount() {
		return $this->amount;
	}

	//////////////////////////////////////////////////

	public function get_currency() {
		return $this->currency;
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
