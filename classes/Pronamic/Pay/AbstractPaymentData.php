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
	private $entrance_code;

	//////////////////////////////////////////////////

	public function __construct() {
		$this->entrance_code = uniqid();
	}

	//////////////////////////////////////////////////

	public abstract function getSource();
	
	public function get_source_id() {
		return $this->getOrderId();
	}

	//////////////////////////////////////////////////
	
	public abstract function getDescription();

	public abstract function getOrderId();

	public abstract function getItems();

	public function getAmount() {
		return $this->getItems()->getAmount();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public abstract function getEMailAddress();
	
	public abstract function getCustomerName();

	public abstract function getOwnerAddress();

	public abstract function getOwnerCity();

	public abstract function getOwnerZip();

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public abstract function getCurrencyAlphabeticCode();

	public function getCurrencyNumericCode() {
		return Pronamic_WordPress_IDeal_Util::transform_currency_code_to_number( $this->getCurrencyAlphabeticCode() );
	}

	//////////////////////////////////////////////////
	// Langauge
	//////////////////////////////////////////////////

	public abstract function getLanguageIso639Code();

	public abstract function getLanguageIso639AndCountryIso3166Code();

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public abstract function getNormalReturnUrl();
	
	public abstract function getCancelUrl();
	
	public abstract function getSuccessUrl();
	
	public abstract function getErrorUrl();

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
	
	public function get_email() {
		if ( is_user_logged_in() ) {
			$user  = wp_get_current_user();

			$email = $user->user_email;
		} else {
			$email = filter_input( INPUT_POST, 'pronamic_ideal_email', FILTER_VALIDATE_EMAIL );
		}
		
		return $email;
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
