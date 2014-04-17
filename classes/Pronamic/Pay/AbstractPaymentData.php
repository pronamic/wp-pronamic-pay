<?php

/**
 * Title: Abstract payment data class
 * Description: 
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
abstract class Pronamic_Pay_AbstractPaymentData implements Pronamic_Pay_PaymentDataInterface {
	public $payment_id;

	private $entrance_code;

	//////////////////////////////////////////////////

	public function __construct() {
		$this->entrance_code = uniqid();
	}

	//////////////////////////////////////////////////

	public abstract function get_source();
	
	public function get_source_id() {
		return $this->get_order_id();
	}

	//////////////////////////////////////////////////
	
	public function get_title() {
		return $this->get_description();
	}
	
	public abstract function get_description();

	public abstract function get_order_id();

	public abstract function getItems();

	public function get_amount() {
		return $this->getItems()->get_amount();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public abstract function get_email();
	
	public abstract function getCustomerName();

	public abstract function getOwnerAddress();

	public abstract function getOwnerCity();

	public abstract function getOwnerZip();

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get the curreny alphabetic code
	 * 
	 *  @return string
	 */
	public abstract function get_currency_alphabetic_code();

	/**
	 * Get currency numeric code
	 * 
	 * @return Ambigous <string, NULL>
	 */
	public function get_currency_numeric_code() {
		return Pronamic_WP_Currency::transform_code_to_number( $this->get_currency_alphabetic_code() );
	}

	/**
	 * Helper function to get the curreny alphabetic code
	 * 
	 * @return string
	 */
	public function get_currency() {
		return $this->get_currency_alphabetic_code();
	}

	//////////////////////////////////////////////////
	// Langauge
	//////////////////////////////////////////////////

	/**
	 * Get the language code (ISO639)
	 * 
	 * @see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
	 * 
	 * @return string
	 */
	public abstract function get_language();

	/**
	 * Get the language (ISO639) and country (ISO3166) code 
	 * 
	 * @see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
	 * @see http://www.iso.org/iso/home/standards/country_codes.htm
	 * 
	 * @return string
	 */
	public abstract function get_language_and_country();

	//////////////////////////////////////////////////
	// Entrance code
	//////////////////////////////////////////////////

	public function get_entrance_code() {
		return $this->entrance_code;
	}
	
	//////////////////////////////////////////////////
	// Issuer
	//////////////////////////////////////////////////

	public function get_issuer_id() {
		return filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );
	}

	/**
	 * Get credit card object
	 *
	 * @return Pronamic_Pay_CreditCard
	 */
	public function get_credit_card() {
		return null;
	}
}
