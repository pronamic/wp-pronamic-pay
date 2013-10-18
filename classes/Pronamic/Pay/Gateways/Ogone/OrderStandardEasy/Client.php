<?php

/**
 * Title: Ogone OrderStandard easy client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Client {
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
	 * Name of the customer
	 * 
	 * @var string
	 */
	private $customerName;

	/**
	 * E-mailaddress
	 * 
	 * @var string
	 */
	private $eMailAddress;

	//////////////////////////////////////////////////
	
	/**
	 * Owner address
	 * 
	 * @var string
	 */
	private $ownerAddress;

	/**
	 * Owner city
	 * 
	 * @var string
	 */
	private $ownerCity;

	/**
	 * Owner ZIP
	 * 
	 * @var string
	 */
	private $ownerZip;

	//////////////////////////////////////////////////

	/**
	 * Accept URL
	 * 
	 * @var string
	 */
	private $accept_url;
	
	/**
	 * Decline URL
	 * 
	 * @var string
	 */
	private $decline_url;
	
	/**
	 * Exception URL
	 * 
	 * @var string
	 */
	private $exception_url;
	
	/**
	 * Cancel URL
	 * 
	 * @var string
	 */
	private $cancel_url;
	
	/**
	 * Back URL
	 * 
	 * @var string
	 */
	private $back_url;
	
	/**
	 * Home URL
	 * 
	 * @var string
	 */
	private $home_url;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL easy object
	 */
	public function __construct() {
		$this->setPaymentType( self::PAYMENT_TYPE_IDEAL );
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
	public function setPaymentServerUrl( $url ) {
		$this->paymentServerUrl = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the PSP id
	 *
	 * @return an PSP id
	 */
	public function getPspId() {
		return $this->pspId;
	}

	/**
	 * Set the PSP id
	 *
	 * @param string $pspId
	 */
	public function setPspId( $psp_id ) {
		$this->pspId = $psp_id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function get_order_id() {
		return $this->orderId;
	}

	/**
	 * Set the order id
	 * AN..max16 (AN = Alphanumeric, free text)
	 *
	 * @param sub id
	 */
	public function setOrderId( $order_id ) {
		$this->orderId = substr( $order_id, 0, 16 );
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
	public function setLanguage( $language ) {
		$this->language = $language;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the description
	 *
	 * @return an description
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Set the description
	 * AN..max32 (AN = Alphanumeric, free text)
	 *
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = substr( $description, 0, 32 );
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
	public function setCurrency( $currency ) {
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
	public function setPaymentType( $paymentType ) {
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
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	public function getCustomerName() {
		return $this->customerName;
	}

	public function setCustomerName( $customerName ) {
		$this->customerName = $customerName;
	}

	//////////////////////////////////////////////////

	public function get_email() {
		return $this->eMailAddress;
	}

	public function setEMailAddress( $eMailAddress ) {
		$this->eMailAddress = $eMailAddress;
	}

	//////////////////////////////////////////////////

	public function getOwnerAddress() {
		return $this->ownerAddress;
	}

	public function setOwnerAddress( $ownerAddress ) {
		$this->ownerAddress = $ownerAddress;
	}

	//////////////////////////////////////////////////

	public function getOwnerCity() {
		return $this->ownerCity;
	}

	public function setOwnerCity( $ownerCity ) {
		$this->ownerCity = $ownerCity;
	}

	//////////////////////////////////////////////////

	public function getOwnerZip() {
		return $this->ownerZip;
	}

	public function setOwnerZip( $ownerZip ) {
		$this->ownerZip = $ownerZip;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	public function get_accept_url() {
		return $this->accept_url;
	}

	public function set_accept_url( $url ) {
		$this->accept_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_decline_url() {
		return $this->decline_url;
	}

	public function set_decline_url( $url ) {
		$this->decline_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_exception_url() {
		return $this->exception_url;
	}

	public function set_exception_url( $url ) {
		$this->exception_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_cancel_url() {
		return $this->cancel_url;
	}

	public function set_cancel_url( $url ) {
		$this->cancel_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_back_url() {
		return $this->back_url;
	}

	public function set_back_url( $url ) {
		$this->back_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_home_url() {
		return $this->home_url;
	}

	public function set_home_url( $url ) {
		$this->home_url = $url;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		return Pronamic_IDeal_IDeal::htmlHiddenFields( array(
			'PSPID'        => $this->getPspId(),

			'orderID'      => $this->get_order_id(),
			'amount'       => Pronamic_WordPress_Util::amount_to_cents( $this->getAmount() ), 
			'currency'     => $this->getCurrency(),
			'language'     => $this->getLanguage(),

			'COM'          => $this->get_description(),
			'PM'           => $this->getPaymentType(),

			'CN'           => $this->getCustomerName(),
			'EMAIL'        => $this->get_email(),
		
			'owneraddress' => $this->getOwnerAddress(),
			'ownertown'    => $this->getOwnerCity(),
			'ownerzip'     => $this->getOwnerZip(),
		
			'accepturl'    => $this->get_accept_url(),
			'declineurl'   => $this->get_decline_url(),
			'exceptionurl' => $this->get_exception_url(),
			'cancelurl'    => $this->get_cancel_url(),
			'backurl'      => $this->get_back_url(),
			'home'         => $this->get_home_url()
		) );
	}
}
