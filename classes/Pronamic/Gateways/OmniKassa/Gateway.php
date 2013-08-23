<?php

/**
 * Title: OmniKassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * The OmniKassa client object
	 * 
	 * @var Pronamic_Gateways_OmniKassa_OmniKassa
	 */
	private $client;
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an OmniKassa gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$this->client = new Pronamic_Gateways_OmniKassa_OmniKassa();
		
		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setKeyVersion( $configuration->keyVersion );
		$this->client->setSecretKey( $configuration->getHashKey() );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data ) {
		$this->set_transaction_id( md5( time() . $data->getOrderId() ) );
		$this->set_action_url( $this->client->getPaymentServerUrl() );

		$this->client->setCustomerLanguage( $data->getLanguageIso639Code() );
		$this->client->setCurrencyNumericCode( $data->getCurrencyNumericCode() );
		$this->client->setOrderId( $data->getOrderId() );
		$this->client->setNormalReturnUrl( home_url( '/' ) );
		$this->client->setAutomaticResponseUrl( home_url( '/' ) );
		$this->client->setAmount( $data->getAmount() );
		$this->client->setTransactionReference( $this->get_transaction_id() );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
}
