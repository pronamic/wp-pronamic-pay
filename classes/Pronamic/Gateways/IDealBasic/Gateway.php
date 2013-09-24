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
	 * @param Pronamic_Gateways_IDealBasic_Config $config
	 */
	public function __construct( Pronamic_Gateways_IDealBasic_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_Gateways_Gateway::METHOD_HTML_FORM );
		$this->set_has_feedback( false );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_Gateways_IDealBasic_IDealBasic();
		
		$this->client->setPaymentServerUrl( $config->url );
		$this->client->setMerchantId( $config->merchant_id );
		$this->client->setSubId( $config->sub_id );
		$this->client->setHashKey( $config->hash_key );
	}
	
	/////////////////////////////////////////////////

	/**
	 * Start an transaction with the specified data
	 * 
	 * @see Pronamic_Gateways_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data ) {
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
