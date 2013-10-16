<?php

/**
 * Title: WordPress payment data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_WP_Pay_PaymentData extends Pronamic_Pay_AbstractPaymentData {
	/**
	 * Constructs and intializes an WordPress iDEAL data proxy
	 */
	public function __construct() {
		parent::__construct();
	}

	//////////////////////////////////////////////////

	/**
	 * Get the ISO 639 language code
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getLanguageIso639Code()
	 * @return string
	 */
	public function getLanguageIso639Code() {
		return Pronamic_WordPress_IDeal_Util::getLanguageIso639Code();
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getLanguageIso639AndCountryIso3166Code()
	 * @return string
	 */
	public function getLanguageIso639AndCountryIso3166Code() {
		return Pronamic_WordPress_IDeal_Util::getLanguageIso639AndCountryIso3166Code();
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function get_normal_return_url() {
		return get_permalink( pronamic_pay_get_page_id( 'unknown' ) );
	}
	
	public function get_cancel_url() {
		return get_permalink( pronamic_pay_get_page_id( 'cancel' ) );
	}
	
	public function get_success_url() {
		return get_permalink( pronamic_pay_get_page_id( 'success' ) );
	}
	
	public function get_error_url() {
		return get_permalink( pronamic_pay_get_page_id( 'error' ) );
	}
}
