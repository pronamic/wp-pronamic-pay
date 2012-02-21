<?php

abstract class Pronamic_IDeal_IDealDataProxy {
	public abstract function getSource();
	
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
		return Pronamic_WordPress_IDeal_Plugin::transformCurrencyCodeToNumber($this->getCurrencyAlphabeticCode());
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
}
