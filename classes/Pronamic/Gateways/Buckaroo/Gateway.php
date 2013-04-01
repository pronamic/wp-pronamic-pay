<?php

/**
 * Title: Easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Slug of this gateway
	 * 
	 * @var string
	 */
	const SLUG = 'buckaroo';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an InternetKassa gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );
		$this->set_slug( self::SLUG );

		$this->client = new Pronamic_Gateways_Buckaroo_Buckaroo();

		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-in.txt';
		$this->client->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );
	
		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-out.txt';
		$this->client->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setHashKey( $configuration->gethashKey() );
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 * 
	 * @param Pronamic_IDeal_IDealDataProxy $data
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		$this->set_transaction_id( md5( time() . $data->getOrderId() ) );
		$this->set_action_url( $this->client->getPaymentServerUrl() );
		$this->client->setMerchantId( $this->client->getMerchantId());
		$this->client->sethashKey( $this->client->gethashKey());

		$this->client->setLanguage( $data->getLanguageIso639AndCountryIso3166Code() );
		$this->client->setCurrency( $data->getCurrencyAlphabeticCode() );
		$this->client->setOrderId( $data->getOrderId() );
		$this->client->setOrderDescription( $data->getDescription() );
		$this->client->setAmount( $data->getAmount() );
		
		$this->client->setAcceptUrl( $data->getNormalReturnUrl() );
		$this->client->setCancelUrl( $data->getCancelUrl() );
		$this->client->setDeclineUrl( $data->getNormalReturnUrl() );
		$this->client->setExceptionUrl( $data->getNormalReturnUrl() );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
}
