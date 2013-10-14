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

	//////////////////////////////////////////////////

	public function get_transaction_id() {
		return $this->transaction_id;
	}
	
	public function add_note( $note ) {
		
	}
}
