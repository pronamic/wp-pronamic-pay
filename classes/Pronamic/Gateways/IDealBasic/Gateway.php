<?php

/**
 * Title: Basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_Gateway extends Pronamic_Gateways_Gateway {
	/**
	 * Construct and intialize an gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		parent::__construct( $configuration );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealBasic_IDealBasic();
		
		$this->client->setPaymentServerUrl( $configuration->getPaymentServerUrl() );
		$this->client->setMerchantId( $configuration->getMerchantId() );
		$this->client->setSubId( $configuration->getSubId() );
		$this->client->setHashKey( $configuration->hashKey );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start an transaction with the specified data
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_IDeal_IDealDataProxy $data ) {
		$this->set_transaction_id( md5( time() . $data->getOrderId() ) );
		$this->set_action_url( $this->client->getPaymentServerUrl() );
		
		$this->client->setLanguage( $data->getLanguageIso639Code() );
		$this->client->setCurrency( $data->getCurrencyAlphabeticCode() );
		$this->client->setPurchaseId( $data->getOrderId() );
		$this->client->setDescription( $data->getDescription() );
		$this->client->setItems( $data->getItems() );
		
		$url = add_query_arg(
			array(
				'gateway'        => 'ideal_basic',
				'transaction_id' => $this->get_transaction_id()
			),
			home_url( '/' )
		); 
		
		$this->client->setCancelUrl( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::CANCELLED, $url ) );
		$this->client->setSuccessUrl( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::SUCCESS, $url ) );
		$this->client->setErrorUrl( add_query_arg( 'status', Pronamic_Gateways_IDealAdvancedV3_Status::FAILURE, $url ) );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get output HTML
	 * 
	 * @see Pronamic_Gateways_Gateway::get_output_html()
	 * @return string
	 */
	public function get_output_html() {
		return $this->client->getHtmlFields();
	}
}
