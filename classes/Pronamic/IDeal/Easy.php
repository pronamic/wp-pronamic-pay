<?php

/**
 * Title: Easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Easy extends Pronamic_IDeal_IDeal {
	/**
	 * An payment type indicator for iDEAL
	 *
	 * @var string
	 */
	const PAYMENT_TYPE_IDEAL = 'iDEAL';

	//////////////////////////////////////////////////

	/**
	 * The URL for testing 
	 * 
	 * @var string
	 */
	private $paymentServerUrl;

	//////////////////////////////////////////////////

	/**
	 * The mercahnt ID
	 * 
	 * @var string
	 */
	private $merchantId;

	/**
	 * The order ID
	 * 
	 * @var string
	 */
	private $orderId;

	/**
	 * The language
	 * 
	 * @var string
	 */
	private $language;

	/**
	 * Description
	 * 
	 * @var string
	 */
	private $description;

	/**
	 * The mode the iDEAL integration is running in (test or production)
	 *
	 * @var int
	 */
	private $mode = self::MODE_TEST;

	/**
	 * The currency
	 *
	 * @var string
	 */
	private $currency;

	/**
	 * Payment method
	 * 
	 * @var string
	 */
	private $paymentType;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL easy object
	 */
	public function __construct() {
		$this->setPaymentType(self::PAYMENT_TYPE_IDEAL);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the payment server URL
	 *
	 * @return the payment server URL
	 */
	public function getPaymentServerUrl() {
		return $this->paymentServerUrl;
	}
	
	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function setPaymentServerUrl($url) {
		$this->paymentServerUrl = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the merchant id
	 *
	 * @return an merchant id
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Set the merchant id
	 *
	 * @param merchant id
	 */
	public function setMerchantId($merchantId) {
		$this->merchantId = $merchantId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Set the order id
	 * AN..max16 (AN = Alphanumeric, free text)
	 *
	 * @param sub id
	 */
	public function setOrderId($orderId) {
		$this->orderId = substr($orderId, 0, 16);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Set the language
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the description
	 *
	 * @return an description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set the description
	 * AN..max32 (AN = Alphanumeric, free text)
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = substr($description, 0, 32);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}
	
	/**
	 * Set the currency
	 *
	 * @return string
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the payment type
	 *
	 * @return an payment type
	 */
	public function getPaymentType() {
		return $this->paymentType;
	}
	
	/**
	 * Set the payment type
	 * AN..max10
	 *
	 * @param string $paymentType an payment type
	 */
	public function setPaymentType($paymentType) {
		$this->paymentType = $paymentType;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount
	 * 
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Set the amount
	 * 
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	public function getCustomerName() {
		return $this->customerName;
	}

	public function setCustomerName($customerName) {
		$this->customerName = $customerName;
	}

	//////////////////////////////////////////////////

	public function getCustomerEMail() {
		return $this->customerEMail;
	}

	public function setCustomerEMail() {
		$this->customerEMail = $customerEMail;
	}

	//////////////////////////////////////////////////

	public function getCustomerAddress() {
		return $this->customerAddress;
	}

	public function setCustomerAddress($customerAddress) {
		$this->customerAddress = $customerAddress;
	}

	//////////////////////////////////////////////////

	public function getCustomerCity() {
		return $this->customerCity;
	}

	public function setCustomerCity($customerCity) {
		$this->customerCity = $customerCity;
	}

	//////////////////////////////////////////////////

	public function getCustomerZip() {
		return $this->customerZip;
	}

	public function setCustomerZip($customerZip) {
		$this->customerZip = $customerZip;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		$html  = '';

		$html .= sprintf('<input type="hidden" name="PSPID" value="%s" />', $this->getMerchantId());

		$html .= sprintf('<input type="hidden" name="orderID" value="%s" />', $this->getOrderId());
		$html .= sprintf('<input type="hidden" name="amount" value="%d" />', Pronamic_IDeal_IDeal::formatPrice($this->getAmount()));
		$html .= sprintf('<input type="hidden" name="currency" value="%s" />', $this->getCurrency());
		$html .= sprintf('<input type="hidden" name="language" value="%s" />', $this->getLanguage());
		$html .= sprintf('<input type="hidden" name="COM" value="%s" />', $this->getDescription());
		$html .= sprintf('<input type="hidden" name="PM" value="%s" />', $this->getPaymentType());

		$html .= sprintf('<input type="hidden" name="CN" value="%s" />', $this->getCustomerName());
		$html .= sprintf('<input type="hidden" name="EMAIL" value="%s" />', $this->getCustomerEMail());

		$html .= sprintf('<input type="hidden" name="owneraddress" value="%s" />', $this->getCustomerAddress());
		$html .= sprintf('<input type="hidden" name="ownertown" value="%s" />', $this->getCustomerCity());
		$html .= sprintf('<input type="hidden" name="ownerzip" value="%s" />', $this->getCustomerZip());

		return $html;
	}
}
