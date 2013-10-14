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
	 * @param Pronamic_Gateways_OmniKassa_Config $config
	 */
	public function __construct( Pronamic_Gateways_OmniKassa_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$this->client = new Pronamic_Gateways_OmniKassa_OmniKassa();
		
		$action_url = Pronamic_Gateways_OmniKassa_OmniKassa::ACTION_URL_PRUDCTION;
		if ( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST ) {
			$action_url = Pronamic_Gateways_OmniKassa_OmniKassa::ACTION_URL_TEST;
		}

		$this->client->set_action_url( $action_url );
		$this->client->set_merchant_id( $config->merchant_id );
		$this->client->set_key_version( $config->key_version );
		$this->client->set_secret_key( $config->secret_key );
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
		$this->set_action_url( $this->client->get_action_url() );

		$this->client->setCustomerLanguage( $data->getLanguageIso639Code() );
		$this->client->setCurrencyNumericCode( $data->getCurrencyNumericCode() );
		$this->client->set_order_id( $data->getOrderId() );
		$this->client->set_normal_return_url( home_url( '/' ) );
		$this->client->set_automatic_response_url( home_url( '/' ) );
		$this->client->set_amount( $data->getAmount() );
		$this->client->set_transaction_reference( $this->get_transaction_id() );
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
