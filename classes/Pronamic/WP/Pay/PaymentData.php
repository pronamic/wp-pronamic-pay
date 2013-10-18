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
	 * The current user
	 * 
	 * @var WP_User
	 */
	private $user;

	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an WordPress iDEAL data proxy
	 */
	public function __construct() {
		parent::__construct();
		
		$this->user = wp_get_current_user();
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
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		$email = null;

		if ( is_user_logged_in() ) {
			$email = $this->user->user_email;
		}
		
		return $email;
	}

	public function getCustomerName() {
		$name = null;

		if ( is_user_logged_in() ) {
			$name = $this->user->user_firstname . ' ' . $this->user->user_lastname;
		}

		return $name;
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
		return get_permalink( pronamic_pay_get_page_id( 'completed' ) );
	}
	
	public function get_error_url() {
		return get_permalink( pronamic_pay_get_page_id( 'error' ) );
	}
}
