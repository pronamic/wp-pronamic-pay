<?php

class Pronamic_Pay_Payment {


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
	
	public $authentication_url;

	//////////////////////////////////////////////////

	public function get_source() {
		return $this->source;
	}

	public function get_source_id() {
		return $this->source_id;
	}
	
	public function get_status() {
		return $this->status;
	}

	//////////////////////////////////////////////////

	public function get_transaction_id() {
		return $this->transaction_id;
	}
	
	public function get_authentication_url() {
		return $this->authentication_url;
	}

	//////////////////////////////////////////////////

	public function set_status( $status ) {
		$this->status = $status;
	}
	
	public function set_authentication_url( $authentication_url ) {
		$this->authentication_url = $authentication_url;
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
}
