<?php

/**
 * Title: iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_IDeal_IDealDataProxy {
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
		return Pronamic_WordPress_IDeal_Util::transformCurrencyCodeToNumber( $this->getCurrencyAlphabeticCode() );
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
}
